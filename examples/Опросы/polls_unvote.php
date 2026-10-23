<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Метод не поддерживается']);
    exit;
}

$poll_id = $_POST['poll_id'] ?? null;
if (!$poll_id) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Не указан ID опроса']);
    exit;
}

$user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? '';
if (empty($user)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Требуется аутентификация']);
    exit;
}
$user = strtoupper(trim($user));

$voted_file = __DIR__ . "/polls/votes/{$poll_id}.json";
if (!file_exists($voted_file)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Вы не голосовали']);
    exit;
}

$voted_data = @json_decode(file_get_contents($voted_file), true);
if (!is_array($voted_data)) {
    $voted_data = [];
}

// Ищем пользователя и сохраняем его голоса ДО удаления
$index = -1;
$user_vote = null;
foreach ($voted_data as $i => $vote) {
    if (isset($vote['user']) && $vote['user'] === $user) {
        $index = $i;
        $user_vote = $vote; // Сохраняем данные до удаления
        break;
    }
}

if ($index === -1) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Вы не голосовали']);
    exit;
}

// Удаляем запись пользователя
unset($voted_data[$index]);
$voted_data = array_values($voted_data); // переиндексируем
file_put_contents($voted_file, json_encode($voted_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// Уменьшаем счётчик голосов
$poll_file = __DIR__ . "/polls/{$poll_id}.json";
if (file_exists($poll_file)) {
    $poll = json_decode(file_get_contents($poll_file), true);

    // Проверяем, есть ли данные о голосах пользователя
    if (!$user_vote || !isset($user_vote['answer'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    // Уменьшаем только те голоса, за которые голосовал пользователь
    foreach ($user_vote['answer'] as $answer_key) {
        $parts = explode('_', $answer_key);
        if (count($parts) !== 2) continue; // защита от некорректных ключей

        $q_id = $parts[0];
        $a_index = (int)$parts[1];

        foreach ($poll['questions'] as &$q) {
            if ($q['id'] === $q_id && isset($q['votes'][$a_index])) {
                $q['votes'][$a_index] = max(0, $q['votes'][$a_index] - 1);
                break;
            }
        }
    }

    file_put_contents($poll_file, json_encode($poll, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;