<?php
session_start();

$id = $_GET['id'] ?? null;
if (!$id) die("Нет ID");

$file = "polls/{$id}.json";
if (!file_exists($file)) die("Опрос не найден");

// Читаем JSON
$json_content = file_get_contents($file);
$poll = json_decode($json_content, true);

if (!$poll) {
    error_log("Failed to decode results JSON: $file" . PHP_EOL, 3, "tmp/error.log");
    die("Ошибка чтения опроса");
}

// Определяем тип голосования
$voting_type = $poll['voting_type'] ?? 'public'; // по умолчанию - публичное

// Показать, сколько человек проголосовало и кто
$voted_file = __DIR__ . "/polls/votes/{$id}.json";
$voted_users = [];
if (file_exists($voted_file)) {
    $voted_users = @json_decode(file_get_contents($voted_file), true);
    $voted_users = is_array($voted_users) ? $voted_users : [];
} else {
	error_log("Failed to decode results VOTES: $voted_file" . PHP_EOL, 3, "tmp/error.log");
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <title>Результаты: <?= htmlspecialchars($poll['title']) ?></title>
    <link rel="stylesheet" type="text/css" href="../css/styles_v20221116.css" />
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        #main {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        }

        h1 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
			max-width: 400px;
			padding-bottom: 15px;
			border-left: 4px solid #4683b7;
			border-top: 4px solid #4683b7;
        }

        h1::before {
            content: "📊";
            font-size: 1.3em;
        }

        .results {
            margin-top: 20px;
        }

        .question-card {
            margin-bottom: 24px;
            border: 1px solid #e0e5ec;
            border-radius: 10px;
            padding: 20px;
            background: #fafbfc;
        }

        .question-title {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
        }

        .answer-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 10px;
            padding: 8px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e8edf4;
        }

        .answer-row:hover {
            border-color: #3498db;
        }

        .answer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .answer-text {
            font-weight: 500;
            color: #34495e;
        }

        .answer-stats {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .bar-container {
            position: relative;
            height: 8px;
            background: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
        }

        .bar {
            background: #3498db;
            height: 100%;
            width: 0;
            transition: width 0.5s ease;
            border-radius: 4px;
        }

        .voters-list {
            margin: 8px 0 0 20px;
            padding: 12px;
            border: 1px dashed #3498db;
            border-radius: 8px;
            display: none;
            background: #f5faff;
            max-height: 200px;
            overflow-y: auto;
        }

        .voters-list li {
            list-style-type: disc;
            margin: 2px 0;
            color: #2c3e50;
        }

        .voters-list:focus-within {
            outline: 2px solid #3498db;
            outline-offset: 2px;
        }

        .summary {
            margin-top: 25px;
            padding: 16px 20px;
            background: #f0f7ff;
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        .summary strong {
            color: #2c3e50;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn:focus {
            outline: 2px solid #1976D2;
            outline-offset: 2px;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .back-link {
            color: #1976D2;
            text-decoration: none;
            font-weight: 500;
            margin-top: 10px;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
        }

        .empty-state::before {
            content: "👋";
            display: block;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        /* Accessibility improvements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <?php include '../print_header.php'; ?>
    <div id="main">
        <h1>Результаты опроса</h1>
        <p style="color: #7f8c8d; font-size: 0.95rem; margin-bottom: 20px;">
            <?= htmlspecialchars($poll['title']) ?>  
            <span style="color: #3498db; margin-left: 8px;">
                (<?= $poll['voting_type'] === 'public' ? 'публичный' : 'анонимный' ?>)
            </span>
        </p>

        <?php if (empty($poll['questions'])): ?>
            <div class="empty-state">
                <p>В этом опросе пока нет вопросов</p>
            </div>
        <?php else: ?>
            <div class="results">
                <?php foreach ($poll['questions'] as $qi => $q): ?>
                    <div class="question-card" id="q<?= $qi + 1 ?>" aria-labelledby="q-title<?= $qi + 1 ?>">
                        <h3 class="question-title" id="q-title<?= $qi + 1 ?>">
                            <?= $qi + 1 ?>. <?= htmlspecialchars($q['text']) ?>
                        </h3>
                        <?php
                        $total = array_sum($q['votes']);
                        foreach ($q['answers'] as $i => $answer):
                            $count = $q['votes'][$i] ?? 0;
                            $percent = $total > 0 ? round(($count / $total) * 100) : 0;

                            // Формируем список голосовавших за этот ответ
                            $voters_for_answer = [];
                            if ($count > 0 && !empty($voted_users)) {
                                $answer_key = $q['id'] . '_' . $i;
                                foreach ($voted_users as $user_vote) {
                                    if (isset($user_vote['answer']) && in_array($answer_key, $user_vote['answer'])) {
                                        $parts = explode('\\', $user_vote['user']);
                                        $login = end($parts); // например, AHMEROVMA

                                        if (!isset($fullname_cache[$login])) {
                                            $fullname_cache[$login] = getFullNameFromAD($user_vote['user']);
                                        }

                                        $voters_for_answer[] = $fullname_cache[$login];
                                    }
                                }
                            }
						
                            ?>
                            <div class="answer-row" role="group" aria-label="Ответ: <?= htmlspecialchars($answer) ?>">
                                <div class="answer-header">
                                    <span class="answer-text"<?= $percent === 0 ? ' style="opacity:0.6"' : '' ?>>
                                        <?= htmlspecialchars($answer) ?>
                                    </span>
                                    <span class="answer-stats">
                                        <?= $count ?> (<?= $percent ?>%)
                                    </span>
                                </div>
                                <div class="bar-container" 
                                     tabindex="<?= $voting_type === 'public' && $percent > 0 ? '0' : '-1' ?>" 
                                     role="button"
                                     aria-expanded="false"
                                     aria-controls="voters-<?= $qi ?>-<?= $i ?>"
                                     onclick="toggleVoters(this)"
                                     <?php if ($voting_type !== 'public' || $percent === 0): ?>style="cursor: not-allowed;"<?php endif; ?>>
                                    <div class="bar" style="width: <?= min($percent * 3, 100) ?>px;"></div>
                                </div>
                                <?php if ($voting_type === 'public' && $count > 0): ?>
                                    <ul id="voters-<?= $qi ?>-<?= $i ?>" class="voters-list" aria-label="Список голосовавших за этот ответ">
                                        <?php foreach ($voters_for_answer as $voter):
                                            $safeVoter = htmlspecialchars($voter);
                                            ?>
                                            <li><?= $safeVoter ?: '<span style="color:#7f8c8d">(не удалось определить)</span>' ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary" role="region" aria-label="Общая статистика">
                <div>
                    <strong>Проголосовало:</strong> <?= count($voted_users) ?> человек
                    <?php if ($voting_type === 'public'): ?>
                        <br><small style="color: #7f8c8d">Доступна детальная статистика — нажмите на полоски</small>
                    <?php endif; ?>
                </div>
                <a href="polls_export_csv.php?id=<?= urlencode($id) ?>" class="btn" aria-label="Скачать результаты в формате CSV">
                    💾 Скачать CSV
                </a>
            </div>

            <div style="margin-top: 25px;">
                <a href="polls/<?= $id ?>.php" class="back-link" aria-label="Вернуться к опросу">
                    ← Вернуться к опросу
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function toggleVoters(element) {
        // Проверка: это только для публичных опросов и ненулевых проголосовавших
        const listId = element.getAttribute('aria-controls');
        const list = document.getElementById(listId);
        const expanded = element.getAttribute('aria-expanded') === 'true';

        if (!list) return;

        list.style.display = expanded ? 'none' : 'block';
        element.setAttribute('aria-expanded', !expanded);

        if (expanded) {
            // Если закрыли — возвращаем фокус кнопке
            element.focus();
        } else {
            // Если открыли — фокус на список, для скринридеров
            setTimeout(() => list.focus(), 100);
        }
    }

    // Инициализация анимации полосок при загрузке
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.bar-container').forEach(container => {
            const bar = container.querySelector('.bar');
            if (bar && parseFloat(bar.style.width) > 0) {
                // Анимация начинается при загрузке
                bar.style.width = bar.style.width;
            }
        });
    });
    </script>
</body>
</html>