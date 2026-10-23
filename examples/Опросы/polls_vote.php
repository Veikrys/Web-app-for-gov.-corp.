<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Недопустимый метод']);
    exit;
}

$poll_id = $_POST['poll_id'] ?? null;
if (!$poll_id) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Не указан ID опроса']);
    exit;
}

// Проверяем, активен ли опрос
if (isset($poll['end_date'])) {
    $end = new DateTime($poll['end_date']);
    $now = new DateTime();
    if ($now > $end) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Срок голосования истёк']);
        exit;
    }
}

// --- Получаем логин пользователя через Windows Auth ---
$user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? '';
if (empty($user)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Требуется аутентификация Windows']);
    exit;
}

// Нормализуем логин (на случай, если приходит в разном регистре)
$user = strtoupper(trim($user));

// --- Путь к файлу с проголосовавшими ---
$voted_file = __DIR__ . "/polls/votes/{$poll_id}.json";

// --- Проверяем, голосовал ли пользователь ---
$already_voted = false;
if (file_exists($voted_file)) {
    $voted_data = @json_decode(file_get_contents($voted_file), true);
    if (is_array($voted_data)) {
        foreach ($voted_data as $vote) {
            if (isset($vote['user']) && $vote['user'] === $user) {
                $already_voted = true;
                break;
            }
        }
    }
} else {
    $voted_data = [];
}

if ($already_voted) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Вы уже голосовали']);
    exit;
}

// --- Читаем сам опрос ---
$file = "polls/{$poll_id}.json";
if (!file_exists($file)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Опрос не найден']);
    exit;
}

$json_content = file_get_contents($file);
$poll = json_decode($json_content, true);
if (!$poll) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка чтения опроса']);
    exit;
}

// --- Обновляем голоса ---
foreach ($_POST['answer'] as $qi => $answers) {
    if (is_array($answers)) {
        foreach ($answers as $ai) {
            if (isset($poll['questions'][$qi]['votes'][$ai])) {
                $poll['questions'][$qi]['votes'][$ai]++;
            }
        }
    } else {
        $ai = $answers;
        if (isset($poll['questions'][$qi]['votes'][$ai])) {
            $poll['questions'][$qi]['votes'][$ai]++;
        }
    }
}

// --- Сохраняем обновлённый опрос ---
$json_data = json_encode($poll, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents($file, $json_data);

// --- Добавляем пользователя в список проголосовавших с указанием ответа ---
$user_vote = [
    'user' => $user,
    'answer' => []
];

// Собираем все выбранные ответы
foreach ($_POST['answer'] as $qi => $answers) {
    if (is_array($answers)) {
        foreach ($answers as $ai) {
            $user_vote['answer'][] = $poll['questions'][$qi]['id'] . '_' . $ai;
        }
    } else {
        $user_vote['answer'][] = $poll['questions'][$qi]['id'] . '_' . $answers;
    }
}

// Если в votes.json хранится массив объектов
$existing_votes = [];
if (file_exists($voted_file)) {
    $existing_votes = @json_decode(file_get_contents($voted_file), true);
    $existing_votes = is_array($existing_votes) ? $existing_votes : [];
}

// Проверяем, не голосовал ли пользователь уже
$user_index = -1;
foreach ($existing_votes as $index => $vote) {
    if (isset($vote['user']) && $vote['user'] === $user) {
        $user_index = $index;
        break;
    }
}

if ($user_index >= 0) {
    // Обновляем существующую запись
    $existing_votes[$user_index] = $user_vote;
} else {
    // Добавляем новую запись
    $existing_votes[] = $user_vote;
}

// Сохраняем обновлённый список
file_put_contents($voted_file, json_encode(array_values($existing_votes), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// --- Ответ ---
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;