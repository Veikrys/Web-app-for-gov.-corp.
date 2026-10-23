<?php

session_start();

$id = $_GET['id'] ?? null;
if (!$id) {
    die('Не указан ID опроса');
}

$file = __DIR__ . "/polls/{$id}.json";
if (!file_exists($file)) {
    die('Опрос не найден');
}

$poll = json_decode(file_get_contents($file), true);
if (!$poll) {
    die('Ошибка чтения данных опроса');
}

// Определяем тип голосования
$voting_type = $poll['voting_type'] ?? 'public'; // по умолчанию - публичное

$voted_file = __DIR__ . "/polls/votes/{$id}.json";
$voted_users = [];
if (file_exists($voted_file)) {
    $voted_users = @json_decode(file_get_contents($voted_file), true);
    $voted_users = is_array($voted_users) ? $voted_users : [];
}

//ОБНОВЛЯТЬ ПРИ СМЕНЕ ПАРОЛЯ ldap_pass !!!

function getFullNameFromAD($username, $domain = 'FSFK') {
    // Настройки подключения к контроллеру домена
    $ldap_host = "ldap://S3700DC01.fsfk.local";
    $ldap_port = 389;
    $ldap_dn = "DC=fsfk,DC=local"; // ← Нужно настроить под вашу структуру
    $ldap_user = "FSFK\\CHERNIKOVAV"; // ← Пользователь с правами на чтение AD
    $ldap_pass = "qqqaaawwwsss!23"; // ← Пароль

    // Убираем домен, если есть
    $parts = explode('\\', $username);
    $samaccountname = end($parts); // например, AHMEROVMA

    // Подключаемся к LDAP
    $ldap_conn = ldap_connect($ldap_host, $ldap_port);
    if (!$ldap_conn) return null;

    // Настройки
    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

    // Авторизуемся
    $bind = ldap_bind($ldap_conn, $ldap_user, $ldap_pass);
    if (!$bind) {
        ldap_close($ldap_conn);
        return null;
    }

    // Поиск пользователя по samaccountname
    $filter = "(samaccountname={$samaccountname})";
    $result = ldap_search($ldap_conn, $ldap_dn, $filter, ['givenName', 'sn', 'displayName']);
    $entries = ldap_get_entries($ldap_conn, $result);

    ldap_close($ldap_conn);

    if ($entries['count'] > 0) {
        $entry = $entries[0];
        // Попробуем собрать ФИО
        if (!empty($entry['displayname'][0])) {
            return $entry['displayname'][0]; // часто уже содержит "Фамилия Имя Отчество"
        }
        if (!empty($entry['sn'][0]) && !empty($entry['givenname'][0])) {
            return $entry['sn'][0] . ' ' . $entry['givenname'][0]; // Фамилия Имя
        }
    }

    return null; // не нашли
}

$fullname_cache = [];

// Транслитерация названия
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

$title_for_file = $poll['title_short'] ?? $poll['title'];
$title_clean = translit($title_for_file);
$date_str = date('Ymd_Hi');
$filename = "{$title_clean}_{$date_str}.csv";

// Заголовки для скачивания
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Pragma: public');

$output = fopen('php://output', 'w');
// Для корректного открытия в Excel — конвертим в Windows-1251
stream_filter_prepend($output, 'convert.iconv.utf-8/cp1251');

// Заголовок
fputcsv($output, [$poll['title'], "Голоса (кол-во)", "ФИО"], ';');
fputcsv($output, []);

foreach ($poll['questions'] as $q) {
    fputcsv($output, [$q['text']], ';');
    foreach ($q['answers'] as $i => $answer) {
        $answer_key = $q['id'] . '_' . $i;
        $count = 0;
        $voters = [];
        
        if ($voting_type === 'public') {
            foreach ($voted_users as $vote) {
                if (isset($vote['answer']) && in_array($answer_key, $vote['answer'])) {
                    $parts = explode('\\', $vote['user']);
                    $login = end($parts);
                    
                    // Кэшируем ФИО
                    if (!isset($fullname_cache[$login])) {
                        $fullname_cache[$login] = getFullNameFromAD($vote['user']);
                    }
                    
                    $voters[] = $fullname_cache[$login] ?: $login; // если ФИО не найдено — используем логин
                }
            }
            fputcsv($output, [$answer, count($voters), implode("\n", $voters)], ';');
        } else {
            $count = 0;
            foreach ($voted_users as $vote) {
                if (isset($vote['answer']) && in_array($answer_key, $vote['answer'])) {
                    $count++;
                }
            }
            fputcsv($output, [$answer, $count], ';');
        }
    }
    fputcsv($output, []);
}

fwrite($output, "\nПроголосовало: " . count($voted_users) . " человек");
fclose($output);
exit;