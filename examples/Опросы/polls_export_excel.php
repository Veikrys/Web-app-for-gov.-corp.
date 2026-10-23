<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/tmp/export_error.log');

session_start();

// Заголовки для скачивания Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="export.xlsx"');
header('Cache-Control: max-age=0');

$id = $_GET['id'] ?? null;
if (!$id) exit;

$file = __DIR__ . "/polls/{$id}.json";
if (!file_exists($file)) exit;

// Читаем опрос
$poll = json_decode(file_get_contents($file), true);
if (!$poll) exit;

// Читаем голоса
$voted_file = __DIR__ . "/polls/votes/{$id}.json";
$voted_users = [];
if (file_exists($voted_file)) {
    $voted_users = @json_decode(file_get_contents($voted_file), true);
    $voted_users = is_array($voted_users) ? $voted_users : [];
}

// Подключаем библиотеку для Excel (требуется Composer)
require_once __DIR__ . '/vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Транслитерация названия для имени файла
function translit($title) {
    $gost = [
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
    ];
    $trans = strtr($title, $gost);
    return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $trans);
}

// Устанавливаем имя файла
$title_clean = translit($poll['title']);
$date_str = date('Ymd_Hi');
header('Content-Disposition: attachment; filename="' . $title_clean . '_' . $date_str . '.xlsx"');

// Заголовок
$sheet->setCellValue('A1', $poll['title']);
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$row = 3;

foreach ($poll['questions'] as $q) {
    // Вопрос
    $sheet->setCellValue("A{$row}", $q['text']);
    $sheet->mergeCells("A{$row}:D{$row}");
    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
    $row++;

    // Варианты
    foreach ($q['answers'] as $i => $answer) {
        $answer_key = $q['id'] . '_' . $i;
        $sheet->setCellValue("A{$row}", $answer);

        // Кто голосовал
        $voters = [];
        foreach ($voted_users as $vote) {
            if (isset($vote['answer']) && in_array($answer_key, $vote['answer'])) {
                $login = end(explode('\\', $vote['user']));
                // Здесь можно подтянуть ФИО, но для упрощения — логин
                $voters[] = $login;
            }
        }

        $sheet->setCellValue("B{$row}", implode(', ', $voters));
        $row++;
    }

    $row++; // пустая строка между вопросами
}

// Автоширина колонок
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Сохраняем в output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');