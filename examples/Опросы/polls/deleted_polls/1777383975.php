<?php 
include '../common.php'; 
// $access=ip_access($db);
// add_rec($access,$db);
// page_access($access,$db);
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
    <title>Тесттесттест2</title>
    <link rel="stylesheet" type="text/css" media="all" href="../css/menu_v20130613.css" />
    <link rel="stylesheet" type="text/css" media="all" href="../css/styles_v20221116.css" />
    <link rel="stylesheet" type="text/css" media="all" href="../css/forms_v20150107.css" />
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <script type="text/javascript" language="javascript" src="../js/jquery_v20130528.js"></script>
    <script type="text/javascript" language="javascript" src="../js/menu_v20130603.js" defer="defer"></script>
        <style>
        .poll { max-width: 600px; margin: 20px auto; font-family: Arial; }
        .question { margin: 30px 0; }
        .results li { background: #eee; margin: 5px 0; padding: 5px; border-radius: 5px; }
        .bar { background: green; color: white; padding: 2px 5px; border-radius: 3px; display: inline-block; min-width: 20px; text-align: center; }
        
        /* Стили для формы голосования */
        .poll-form { 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            background: #f9f9f9; 
            font-family: Arial, sans-serif;
        }
        .poll-form h3 { 
            margin: 0 0 15px 0; 
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
        }
        
        .poll-form button { 
            background: #1976D2; 
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
        
        .error-message { 
            color: #d32f2f; 
            font-weight: bold; 
            margin: 15px 0; 
            background: #ffebee; 
            padding: 10px; 
            border-radius: 4px; 
            border-left: 4px solid #d32f2f;
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
    <?php print_header($access, $db); ?>
    <div id="main">
        <h2>Тесттесттест2</h2>
		<?php
			$start = str_replace('T', ' ', '2026-04-28T16:38');
			$end = str_replace('T', ' ', '2026-04-30T16:38');
			
			$start2 = str_replace('-', '.', $start);
			$end2 = str_replace('-', '.', $end);
		?>
        <p><b>Период:</b> <?= htmlspecialchars($start2)?> - <?= htmlspecialchars($end2)?></p>

        <?php
        $now = new DateTime();
        $end = new DateTime('2026-04-30T16:38');
        $is_active = $now <= $end;
        if (!$is_active): ?>
            <div class="error-message">
                Опрос завершён
            </div>
        <?php endif; ?>

        <?php if (!$is_active): ?>
                <style>
        .poll { max-width: 600px; margin: 20px auto; font-family: Arial; }
        .question { margin: 30px 0; }
        .results li { background: #eee; margin: 5px 0; padding: 5px; border-radius: 5px; }
        .bar { background: green; color: white; padding: 2px 5px; border-radius: 3px; display: inline-block; min-width: 20px; text-align: center; }
        
        /* Стили для формы голосования */
        .poll-form { 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            background: #f9f9f9; 
            font-family: Arial, sans-serif;
        }
        .poll-form h3 { 
            margin: 0 0 15px 0; 
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
        }
        
        .poll-form button { 
            background: #1976D2; 
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
        
        .error-message { 
            color: #d32f2f; 
            font-weight: bold; 
            margin: 15px 0; 
            background: #ffebee; 
            padding: 10px; 
            border-radius: 4px; 
            border-left: 4px solid #d32f2f;
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
        <?php endif; ?>

        <div class="poll-form">
            <form action="../polls_vote.php" method="post">
                <input type="hidden" name="poll_id" value="1777383975">
            <div class="question">
                <h3><strong>ТесттесттестТесттесттестТесттесттест2</strong></h3>

                <label><input type="radio" name="answer[0]" value="0"> <span>ТесттесттестТесттесттест2</span></label><br>

                <label><input type="radio" name="answer[0]" value="1"> <span>ТесттесттестТесттесттест2</span></label><br>
            </div>

            <div class="question">
                <h3><strong>ТесттесттестТесттесттестТесттесттест2</strong></h3>
                <label><input type="checkbox" name="answer[1][]" value="0"> <span>ТесттесттестТесттесттест2</span></label><br>
                <label><input type="checkbox" name="answer[1][]" value="1"> <span>ТесттесттестТесттесттест2</span></label><br>
                <label><input type="checkbox" name="answer[1][]" value="2"> <span>ТесттесттестТесттесттест2</span></label><br>
            </div>

            <button type="button" onclick="submitVote()" <?= !$is_active ? 'disabled' : '' ?>>
                Проголосовать
            </button>
        </form>

        <hr>
        
        <span id="poll-actions">
                <a href="#" onclick="unvote(); return false;">  Отменить голос</a>
				| <a href="mailto:?subject=%D0%A2%D0%B5%D1%81%D1%82%D1%82%D0%B5%D1%81%D1%82%D1%82%D0%B5%D1%81%D1%822&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1777383975.php">Поделиться</a>
                <?php 
				$user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'unknown';
				error_log("USERrrr: " . $user . PHP_EOL, 3, "tmp/error.log");
				error_log("CREATORrrr: " . $data['creator'] . PHP_EOL, 3, "tmp/error.log");
				if (strtoupper(trim($user)) === 'FSFK\CHERNIKOVAV' || strtoupper(trim($user)) === "FSFK\SOLDATOVAN" || strtoupper(trim($user)) === "FSFK\SENICHKINOV"):
				?>
					| <a href="../polls_results.php?id=1777383975">Посмотреть результаты</a>
					| <a href="#" style="color:red" onclick="deletePoll(); return false;">Удалить опрос</a>
                <?php endif; ?>
        </span>
    </div>
    <?php footer($access, $db); ?>
	
	<script>
        const POLL_ID = '1777383975';
        const POLL_END_DATE = '2026-04-30T16:38'; // формат: 2025-04-10T18:00

        function isPollActive() {
            const now = new Date();
            const end = new Date(POLL_END_DATE);
            return now <= end;
        }

        // Блокировка голосования на стороне клиента
        if (!isPollActive()) {
            document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(el => {
                el.disabled = true;
            });
            const button = document.querySelector('button[onclick="submitVote()"]');
            if (button) button.disabled = true;
        }

        function submitVote() {
            const form = document.querySelector('form');
            const formData = new FormData(form);

            fetch('../polls_vote.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
					alert("Спасибо за ваш голос!");
                    window.location.reload();
                }
            })
            .catch(error => {
                alert("Произошла ошибка при голосовании");
                console.error(error);
            });
        }

        function unvote() {
            if (!confirm("Вы уверены, что хотите отменить свой голос?")) return;

            fetch('../polls_unvote.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'poll_id=' + encodeURIComponent(POLL_ID)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Голос отменён");
                    window.location.reload();
                } else {
                    alert(data.error || "Ошибка");
                }
            })
            .catch(err => {
                alert("Ошибка при отмене голоса");
            });
        }

        function deletePoll() {
            if (!confirm("Вы уверены, что хотите УДАЛИТЬ этот опрос? Это действие нельзя отменить.")) return;

            fetch('../polls_delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'poll_id=' + encodeURIComponent(POLL_ID)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Опрос удалён");
                    window.location.href = '../main.php'; // или другая целевая страница
                } else {
                    alert(data.error || "Ошибка");
                }
            })
            .catch(err => {
                alert("Ошибка при удалении опроса");
            });
        }
    </script>
</body>
</html>