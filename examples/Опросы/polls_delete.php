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

$poll_file = __DIR__ . "/polls/{$poll_id}.json";
$php_file = __DIR__ . "/polls/{$poll_id}.php";

if (!file_exists($poll_file)) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Опрос не существует']);
    exit;
}

$poll = json_decode(file_get_contents($poll_file), true);
if ($poll['creator'] !== $user && $user !== "FSFK\SOLDATOVAN" && $user !== "FSFK\SENICHKINOV" && $user !== "FSFK\CHERNIKOVAV") {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'У вас нет прав на удаление']);
    exit;
}

// Папка для удалённых опросов
$deleted_dir = __DIR__ . '/polls/deleted_polls';
if (!is_dir($deleted_dir)) {
    if (!mkdir($deleted_dir, 0755, true)) {
        error_log("Failed to create deleted_polls directory: $deleted_dir");
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Не удалось создать папку для удалённых опросов']);
        exit;
    }
}

// Перемещаем .json и .php файлы
$dest_json = $deleted_dir . "/{$poll_id}.json";
$dest_php = $deleted_dir . "/{$poll_id}.php";

if (!rename($poll_file, $dest_json)) {
    error_log("Failed to move JSON file to $dest_json");
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Не удалось переместить JSON-файл']);
    exit;
}

if (file_exists($php_file)) {
    if (!rename($php_file, $dest_php)) {
        error_log("Failed to move PHP file to $dest_php");
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Не удалось переместить PHP-файл']);
        exit;
    }
}

// Логируем удаление
$log_entry = sprintf(
    "ID=%s | Title=\"%s\" | Deleted at=%s | By=%s\n",
    $poll_id,
    $poll['title'],
    date('Y-m-d H:i:s'),
    $user
);
file_put_contents(__DIR__ . '/polls/deleted_log.txt', $log_entry, FILE_APPEND | LOCK_EX);

header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;