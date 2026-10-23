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
    <title>Опрос для проверки участия в голосовании за благоустройство города и в регистрации на ЕР</title>
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
    </style>
</head>
<body>
    <?php print_header($access, $db); ?>
    <div id="main">
        <h2>Опрос для проверки участия в голосовании за благоустройство города и в регистрации на ЕР</h2>
		<?php
			$start = str_replace('T', ' ', '2026-04-24T11:03');
			$end = str_replace('T', ' ', '2026-04-25T11:03');
			
			$start2 = str_replace('-', '.', $start);
			$end2 = str_replace('-', '.', $end);
		?>
        <p><b>Период:</b> <?= htmlspecialchars($start2)?> - <?= htmlspecialchars($end2)?></p>

        <?php
        $now = new DateTime();
        $end = new DateTime('2026-04-25T11:03');
        $is_active = $now <= $end;
        if (!$is_active): ?>
            <div style="color: #d32f2f; font-weight: bold; margin: 15px 0; font-size: 1.1em;">
                Опрос завершён
            </div>
        <?php endif; ?>

        <?php if (!$is_active): ?>
            <style>
                .poll form { pointer-events: none; opacity: 0.6; }
                .poll input[type="radio"], 
                .poll input[type="checkbox"] { pointer-events: none; }
            </style>
        <?php endif; ?>

        <form action="../polls_vote.php" method="post">
            <input type="hidden" name="poll_id" value="1777018022">
            <div class="question">
                <h3><strong>Вы приняли участие в голосовании на тему отбора территорий для благоустройства?</strong></h3>
				
				<div>
					<input type="radio" name="answer[0]" value="0">
					<label>Да</label>
				</div>
				<br>
				
				<div>
					<input type="radio" name="answer[0]" value="1">
					<label>Нет</label>
				</div>
				<br>
            </div>

            <div class="question">
                <h3><strong>Вы прошли регистрацию для Предварительного голосования в ЕР?</strong></h3>

                <label><input type="radio" name="answer[1]" value="0"> Да</label><br>

                <label><input type="radio" name="answer[1]" value="1"> Нет</label><br>
            </div>

            <button type="button" onclick="submitVote()" <?= !$is_active ? 'disabled' : '' ?>>
                Проголосовать
            </button>
        </form>

        <hr>
        
        <span id="poll-actions">
                <a href="#" onclick="unvote(); return false;">  Отменить голос</a>
				| <a href="mailto:?subject=%D0%9E%D0%BF%D1%80%D0%BE%D1%81%20%D0%B4%D0%BB%D1%8F%20%D0%BF%D1%80%D0%BE%D0%B2%D0%B5%D1%80%D0%BA%D0%B8%20%D1%83%D1%87%D0%B0%D1%81%D1%82%D0%B8%D1%8F%20%D0%B2%20%D0%B3%D0%BE%D0%BB%D0%BE%D1%81%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B8%20%D0%B7%D0%B0%20%D0%B1%D0%BB%D0%B0%D0%B3%D0%BE%D1%83%D1%81%D1%82%D1%80%D0%BE%D0%B9%D1%81%D1%82%D0%B2%D0%BE%20%D0%B3%D0%BE%D1%80%D0%BE%D0%B4%D0%B0%20%D0%B8%20%D0%B2%20%D1%80%D0%B5%D0%B3%D0%B8%D1%81%D1%82%D1%80%D0%B0%D1%86%D0%B8%D0%B8%20%D0%BD%D0%B0%20%D0%95%D0%A0&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1777018022.php">Поделиться</a>
                <?php 
				$user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'unknown';
				error_log("USERrrr: " . $user . PHP_EOL, 3, "tmp/error.log");
				error_log("CREATORrrr: " . $data['creator'] . PHP_EOL, 3, "tmp/error.log");
				if (strtoupper(trim($user)) === 'FSFK\CHERNIKOVAV' || strtoupper(trim($user)) === 'FSFK\SENICHKINOV' || strtoupper(trim($user)) === 'FSFK\SOLDATOVAN'):
				?>
					| <a href="../polls_results.php?id=1777018022">Посмотреть результаты</a>
					| <a href="#" style="color:red" onclick="deletePoll(); return false;">Удалить опрос</a>
                <?php endif; ?>
        </span>
    </div>
    <?php footer($access, $db); ?>
	
	<script>
        const POLL_ID = '1777018022';
        const POLL_END_DATE = '2026-04-25T11:03'; // формат: 2025-04-10T18:00

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