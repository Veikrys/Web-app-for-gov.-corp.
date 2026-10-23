<?php
include 'common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: polls_manager.php");
    exit;
}

function cp1251_to_utf8_recursive($data) {
    if (is_string($data)) {
        return iconv('windows-1251', 'UTF-8//IGNORE', $data);
    } elseif (is_array($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = cp1251_to_utf8_recursive($value);
        }
        return $result;
    } else {
        return $data;
    }
}

// Получаем данные из формы
$title = cp1251_to_utf8_recursive(trim($_POST['title']));
// Сокращённое техническое название
$technicalTitle = getTechnicalTitle($title);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$questions = cp1251_to_utf8_recursive($_POST['questions'] ?? []);
$types = $_POST['type'] ?? [];
$answers = cp1251_to_utf8_recursive($_POST['answers'] ?? []);
$voting_type = $_POST['voting_type'] ?? 'public';

// Переиндексируем массивы, чтобы избежать "дыр" в индексах
$questions = array_values($questions);
$types = array_values($types);
$answers = array_values($answers);

// Генерируем уникальный ID
$id = time();

// Формируем структуру опроса
// Получаем создателя
error_log("USER:" . $_SERVER['AUTH_USER'] .PHP_EOL, 3, "tmp/error.log");
$creator = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'unknown';
$creator = strtoupper(trim($creator));

error_log("CREATOR:" . $creator .PHP_EOL, 3, "tmp/error.log");

function getTechnicalTitle($fullTitle, $maxLength = 60) {
    $fullTitle = trim($fullTitle);
    if (mb_strlen($fullTitle) <= $maxLength) {
        return $fullTitle;
    }

    // Работаем с UTF-8
    $short = mb_substr($fullTitle, 0, $maxLength);
    $lastSpacePos = mb_strrpos($short, ' ');

    // Если последний пробел есть и не первый символ — обрезаем после слова
    if ($lastSpacePos > 0) {
        $short = mb_substr($fullTitle, 0, $lastSpacePos);
        return $short . '…';
    }

    // Если до 60-го символа нет пробелов, но это начало слова — просто обрезаем и добавляем "…"
    return mb_substr($fullTitle, 0, $maxLength) . '…';
}

$poll = [
    'id' => $id,
    'title' => $title,
    'title_short' => $technicalTitle,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'creator' => $creator,  // ← Добавили создателя
    'voting_type' => $voting_type,
    'questions' => []
];

$questionCounter = 1;

// Обработка вопросов и ответов
foreach ($questions as $i => $q) {
    $question_text = trim($q);
    if (empty($question_text)) continue;

    $ans = $answers[$i] ?? [];
    $ans = array_filter(array_map('trim', $ans)); // убираем пустые и лишние пробелы
    if (empty($ans)) continue; // пропускаем вопросы без ответов

    $poll['questions'][] = [
		'id' => 'q' . $questionCounter,
        'text' => $question_text,
        'type' => $types[$i] ?? 'single',
        'answers' => array_values($ans), // снова переиндексируем
        'votes' => array_fill(0, count($ans), 0)
    ];
	
	$questionCounter++;
}

// Сохраняем JSON
$json_data = json_encode($poll, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if ($json_data === false) {
    error_log("JSON encode failed: " . json_last_error_msg() . PHP_EOL, 3, "tmp/error.log");
    die("Ошибка: не удалось сохранить опрос. Используйте только допустимые символы.");
}
// $json_data = iconv('windows-1251', 'UTF-8', $json_data);
file_put_contents("polls/{$id}.json", $json_data);
error_log("JSON saved in UTF-8: polls/{$id}.json" . PHP_EOL, 3, "tmp/error.log");

error_log("\nPoll created: ID=$id, Title=$title, Questions=" . count($poll['questions']) .PHP_EOL, 3, "tmp/error.log");

// Обновляем индекс
$index = file_exists("polls/index.json") ? json_decode(file_get_contents("polls/index.json"), true) : [];
$index[] = ['id' => $id, 'title' => $title];
$index_json = json_encode($index, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents("polls/index.json", $index_json);

// Генерируем страницу
generate_poll_page($id, $access, $db);

// Перенаправляем на страницу опроса
header("Location: polls/{$id}.php");
// $access=ip_access($db);
// add_rec($access,$db);
// page_access($access,$db);
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: max-age=0");
header("Content-Type: text/html; charset=windows-1251");
exit;

/**
 * Генерирует HTML-страницу опроса (ТЕПЕРЬ ПХП)
 */
function generate_poll_page($id, $access, $db) {
    error_log("generate_poll_page started for ID=$id" . PHP_EOL, 3, "tmp/error.log");

    $file = __DIR__ . "/polls/{$id}.json";
    if (!file_exists($file)) {
        error_log("JSON file not found: $file" . PHP_EOL, 3, "tmp/error.log");
        return;
    }

    $json_content = file_get_contents($file);
    error_log("JSON content length: " . strlen($json_content) . PHP_EOL, 3, "tmp/error.log");

    $data = json_decode($json_content, true);
    if (!$data) {
        error_log("JSON decode failed. Last error: " . json_last_error_msg() . PHP_EOL, 3, "tmp/error.log");
        return;
    }

    error_log("JSON loaded, title=" . $data['title'] . PHP_EOL, 3, "tmp/error.log");

    // Генерируем PHP-код файла, а не просто HTML
    $php_code = <<<PHP
<?php 
include '../common.php'; 
// \$access=ip_access(\$db);
// add_rec(\$access,\$db);
// page_access(\$access,\$db);
header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Cache-Control: max-age=0");
header("Content-Type: text/html; charset=windows-1251");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=8, chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" lang="ru" xml:lang="ru" />
    <meta http-equiv="Content-Language" content="ru-ru" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="ImageToolbar" content="no" />
    <title>{$data['title']}</title>
    <link rel="stylesheet" type="text/css" media="all" href="../css/menu_v20130613.css" />
    <link rel="stylesheet" type="text/css" media="all" href="../css/styles_v20221116.css" />
    <link rel="stylesheet" type="text/css" media="all" href="../css/forms_v20150107.css" />
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <script type="text/javascript" language="javascript" src="../js/jquery_v20130528.js"></script>
    <script type="text/javascript" language="javascript" src="../js/menu_v20130603.js" defer="defer"></script>
        <style>
        .poll { max-width: 600px; margin: 20px auto; font-family: Arial; }
        .results li { background: #eee; margin: 5px 0; padding: 5px; border-radius: 5px; }
        .bar { background: green; color: white; padding: 2px 5px; border-radius: 3px; display: inline-block; min-width: 20px; text-align: center; }
        
        /* Стили для формы голосования */
        .poll-form { 
            max-width: 700px; 
            margin: 20px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            background: #f9f9f9; 
            font-family: Arial, sans-serif;
        }
		
		.poll-form h1 { 
            margin: 0 0 15px 0; 
            padding-bottom: 20px;
			padding-top: 10px;
            color: #333;
			font-size: 1.5em;
			border-left: 4px solid #4683b7;
			border-top: 4px solid #4683b7;
        }
		
        .poll-form h3 { 
            margin: 0 240px 15px 0; 
            padding-bottom: 10px; 
            border-bottom: 1px solid #eee; 
            color: #333;
        }
        .poll-form .answers-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 500px;
            margin-top: 10px;
        }
        
        .poll-form label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 0;
            cursor: pointer;
            line-height: 1.5;
            padding: 12px;
            border: 1px solid #eee;
            border-radius: 6px;
            background: white;
            transition: all 0.2s ease;
            width: 100%;
            box-sizing: border-box;
			margin-bottom: 8px;
        }
		
		.poll-form label:last-child {
			margin-bottom: 20px;
		}
        
        .poll-form label:hover {
            border-color: #1976D2;
            background-color: #f5f9ff;
            transform: translateY(-1px);
        }
        
        .poll-form input[type="radio"],
        .poll-form input[type="checkbox"] {
            margin-top: 4px;
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }
        
        .poll-form label span {
            flex: 1;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            line-height: 1.4;
			text-align: left;
        }
        
        .poll-form button { 
            background: #4683b7; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            font-size: 16px; 
            border-radius: 4px; 
            cursor: pointer; 
            margin-top: 15px; 
            transition: background 0.3s;
        }
        
        .poll-form button:hover:not(:disabled) { 
            background: #1565C0; 
        }
        
        .poll-form button:disabled { 
            background: #999; 
            cursor: not-allowed; 
        }
        
        .poll-form .error-message { 
            color: #d32f2f; 
            font-weight: bold; 
            margin: 15px 0; 
            background: #ffebee; 
            padding: 10px; 
            border-radius: 4px; 
			border-left: 4px solid #d32f2f;
        }
		
		.question {
			display: table;
			margin: 20px 0;
			padding: 15px 0 0;
			border-top: 1px dashed #eee;
		}

        /* Стили для действий (результаты, удаление и т.д.) */
        #poll-actions {
            margin-top: 15px;
            padding: 10px 0;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9em;
        }
        #poll-actions a {
            color: #1976D2;
            text-decoration: none;
            margin: 0 5px;
        }
        #poll-actions a:hover {
            text-decoration: underline;
        }
        #poll-actions a\[href=\"mailto\"\]:before {
            content: " ";
            margin-right: 2px;
        }
		
    </style>
</head>
<body>
    <?php print_header(\$access, \$db); ?>
    <div id="main">
		<div class="poll-form">
			<h1>{$data['title']}</h1>
			<?php
				\$start = str_replace('T', ' ', '{$data['start_date']}');
				\$end = str_replace('T', ' ', '{$data['end_date']}');
				
				\$start2 = str_replace('-', '.', \$start);
				\$end2 = str_replace('-', '.', \$end);
			?>

			<?php
			\$now = new DateTime();
			\$end = new DateTime('{$data['end_date']}');
			\$is_active = \$now <= \$end;
			if (!\$is_active): ?>
				<div class="error-message">
					Опрос завершён
				</div>
			<?php endif; ?>

        
            <div class="poll-period" style="text-align: right; margin-bottom: 15px;">
                <strong style="color: #4683b7; border-bottom: 1px dotted #1976D2;">
                    Опрос действует с <?= htmlspecialchars(\$start2)?> по <?= htmlspecialchars(\$end2)?>
                </strong>
            </div>

            <form action="../polls_vote.php" method="post">
                <input type="hidden" name="poll_id" value="$id">
PHP;

    // Добавляем вопросы
    foreach ($data['questions'] as $qi => $q) {
        $php_code .= "\n            <div class=\"question\">\n                <h3><strong>" . htmlspecialchars($q['text']) . "</strong></h3>\n";

        if ($q['type'] === 'single') {
            foreach ($q['answers'] as $ai => $a) {
                $php_code .= "\n                <label><input type=\"radio\" name=\"answer[$qi]\" value=\"$ai\"> <span >" . htmlspecialchars($a) . "</span></label><br>\n";
            }
        } else {
            foreach ($q['answers'] as $ai => $a) {
                $php_code .= "                <label><input type=\"checkbox\" name=\"answer[$qi][]\" value=\"$ai\"> <span>" . htmlspecialchars($a) . "</span></label><br>\n";
            }
        }

        $php_code .= "            </div>\n";
    }

    error_log("Title before iconv: " . bin2hex($data['title']) . PHP_EOL, 3, "tmp/error.log");
    // Подготавливаем тему: перекодируем из windows-1251 → UTF-8 → rawurlencode (с %20 вместо +)
    // $title_utf8 = iconv('windows-1251', 'UTF-8//IGNORE', $data['title']);
    $subject = rawurlencode($data['title']);  // используем %20 вместо +
    
    // Тело сообщения — тоже с rawurlencode, и пробелы будут корректны
    $body_text = "Пройдите опрос: https://s3700web01.fsfk.local/polls/{$id}.php";
    $body = rawurlencode($body_text);

    // Вставляем в PHP-код
    $php_code .= "\n            <button type=\"button\" onclick=\"submitVote()\" <?= !\$is_active ? 'disabled' : '' ?>>\n                Проголосовать\n            </button>\n        </form>\n\n        <hr>\n        \n        <span id=\"poll-actions\">\n                <a href=\"#\" onclick=\"unvote(); return false;\">  Отменить голос</a>";

    // Проверяем права
    $php_code .= <<<PHP

                <?php 
                \$user = \$_SERVER['AUTH_USER'] ?? \$_SERVER['REMOTE_USER'] ?? 'unknown';
                \$user = strtoupper(trim(\$user));
                
                \$supervisors = ['FSFK\SoldatovAN', 'FSFK\SenichkinOV', 'FSFK\ChernikovAV']; 
                \$supervisorsUpCase = array_map('strtoupper', \$supervisors);
                
                \$managers = ['FSFK\VasinAN','FSFK\AnisimovaAA','FSFK\BalyberdinaNV','FSFK\RyzhovaNN','FSFK\SenichkinaOI','FSFK\HvostenkoNP','FSFK\GlazkovaOG','FSFK\KozlovaOA','FSFK\KostylevaOV','FSFK\AnikinaLN','FSFK\ErmakovaNA','FSFK\VasilevaEV','FSFK\ArtamonovaLL','FSFK\BubnovaVL','FSFK\AndryuhinaTG','FSFK\DvoryanchikovaTN','FSFK\DocenkoDV','FSFK\SeynerOI','FSFK\BelovaOYU','FSFK\KravchenkoNV','FSFK\BoyarovaPA','FSFK\KorostelevIA','FSFK\ZharkovaYUI','FSFK\DmitrievaSV','FSFK\ProdanPV','FSFK\SvechnikovaEV','FSFK\ChistyakovaVV','FSFK\ArseevaEYU','FSFK\NosovaOV','FSFK\ChuikinVYU','FSFK\ZaharovAV','FSFK\AndrianovaEYU','FSFK\KizinaNV','FSFK\PlatonovVA','FSFK\FinakinaEYU','FSFK\MedvedevaVS','FSFK\GrebennikovVA','FSFK\GorbachovaNV','FSFK\AleshinaOV','FSFK\KolyazinaEYU'];  
                \$managersUpCase = array_map('strtoupper', \$managers);
                
                if (in_array(\$user, \$supervisorsUpCase, true) || \$user === '{$data['creator']}') {
                    echo ' | <a href="../polls_results.php?id={$id}">Посмотреть результаты</a>';
                    echo ' | <a href="mailto:?subject={$subject}&amp;body={$body}">Поделиться</a>';
                    echo ' | <a href="#" onclick="changePoll(); return false;">Редактировать опрос</a>';
                    echo ' | <a href="#" style="color:red" onclick="deletePoll(); return false;">Удалить опрос</a>';
                } elseif (in_array(\$user, \$managersUpCase, true)) {
                    echo ' | <a href="../polls_results.php?id={$id}">Посмотреть результаты</a>';
                    echo ' | <a href="mailto:?subject={$subject}&amp;body={$body}">Поделиться</a>';
                }               
                ?>
    PHP;

    // Всегда показываем "Отменить голос" — уже добавлен выше

    $php_code .= "\n        </span>\n    </div>\n    <?php footer(\$access, \$db); ?>\n\t\n\t<script>\n        const POLL_ID = '$id';\n        const POLL_END_DATE = '{$data['end_date']}'; // формат: 2025-04-10T18:00\n\n        function isPollActive() {\n            const now = new Date();\n            const end = new Date(POLL_END_DATE);\n            return now <= end;\n        }\n\n        // Блокировка голосования на стороне клиента\n        if (!isPollActive()) {\n            document.querySelectorAll('input[type=\"radio\"], input[type=\"checkbox\"]').forEach(el => {\n                el.disabled = true;\n            });\n            const button = document.querySelector('button[onclick=\"submitVote()\"]');\n            if (button) button.disabled = true;\n        }\n\n        function submitVote() {\n            // Проверка: на каждый вопрос с типом radio или checkbox должен быть выбран хотя бы один ответ\n            let allQuestionsAnswered = true;\n            const questions = document.querySelectorAll('.question');\n            \n            questions.forEach(question => {\n                const radios = question.querySelectorAll('input[type=\"radio\"]');\n                const checkboxes = question.querySelectorAll('input[type=\"checkbox\"]');\n                \n                let answered = false;\n                if (radios.length > 0) {\n                    answered = Array.from(radios).some(radio => radio.checked);\n                } else if (checkboxes.length > 0) {\n                    answered = Array.from(checkboxes).some(checkbox => checkbox.checked);\n                }\n                \n                if (!answered) {\n                    allQuestionsAnswered = false;\n                }\n            });\n            \n            if (!allQuestionsAnswered) {\n                alert('Пожалуйста, ответьте на все вопросы, выбрав хотя бы один вариант в каждом.');\n                return;\n            }\n            \n            const form = document.querySelector('form');\n            const formData = new FormData(form);\n\n            fetch('../polls_vote.php', {\n                method: 'POST',\n                body: formData\n            })\n            .then(response => response.json())\n            .then(data => {\n                if (data.error) {\n                    alert(data.error);\n                } else {\n                    alert(\"Спасибо за ваш голос!\");\n                    window.location.reload();\n                }\n            })\n            .catch(error => {\n                alert(\"Произошла ошибка при голосовании\");\n                console.error(error);\n            });\n        }\n\n        function unvote() {\n            if (!isPollActive()) {\n                alert('Нельзя отменить голос, так как опрос уже завершён.');\n                return;\n            }\n            \n            if (!confirm(\"Вы уверены, что хотите отменить свой голос?\")) return;\n\n            fetch('../polls_unvote.php', {\n                method: 'POST',\n                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },\n                body: 'poll_id=' + encodeURIComponent(POLL_ID)\n            })\n            .then(response => response.json())\n            .then(data => {\n                if (data.success) {\n                    alert(\"Голос отменён\");\n                    window.location.reload();\n                } else {\n                    alert(data.error || \"Ошибка\");\n                }\n            })\n            .catch(err => {\n                alert(\"Ошибка при отмене голоса\");\n            });\n        }\n\n        function deletePoll() {\n            if (!confirm(\"Вы уверены, что хотите УДАЛИТЬ этот опрос? Это действие нельзя отменить.\")) return;\n\n            fetch('../polls_delete.php', {\n                method: 'POST',\n                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },\n                body: 'poll_id=' + encodeURIComponent(POLL_ID)\n            })\n            .then(response => response.json())\n            .then(data => {\n                if (data.success) {\n                    alert(\"Опрос удалён\");\n                    window.location.href = '../main.php'; // или другая целевая страница\n                } else {\n                    alert(data.error || \"Ошибка\");\n                }\n            })\n            .catch(err => {\n                alert(\"Ошибка при удалении опроса\");\n            });\n        }\n\t\t\n\t\tfunction changePoll() {\n            if (!confirm(\"Вы уверены, что хотите редактировать этот опрос?\")) return;\n            window.location.href = `../polls_change_form.php?id=$id`;
        }\n    </script>\n</body>\n</html>";

    $php_code_win1251 = iconv('UTF-8', 'windows-1251//IGNORE', $php_code);

    if ($php_code_win1251 === false) {
        error_log("Failed to convert to windows-1251, using raw" . PHP_EOL, 3, "tmp/error.log");
        $php_code_win1251 = $php_code; // fallback
    }

    $output_file = __DIR__ . "/polls/{$id}.php";
    $result = file_put_contents($output_file, $php_code_win1251);

    if ($result === false) {
        error_log("Failed to write file: $output_file" . PHP_EOL, 3, "tmp/error.log");
        die("Не удалось сохранить файл опроса");
    }

    error_log("Poll page created: $output_file, size=$result bytes" . PHP_EOL, 3, "tmp/error.log");
}