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
    <title>Опрос после обучения трудоустройство после увольнения, дача согласия за замещение должности</title>
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
    <?php print_header($access, $db); ?>
    <div id="main">
		<div class="poll-form">
			<h1>Опрос после обучения трудоустройство после увольнения, дача согласия за замещение должности</h1>
			<?php
				$start = str_replace('T', ' ', '2026-09-07T11:47');
				$end = str_replace('T', ' ', '2026-10-07T11:47');
				
				$start2 = str_replace('-', '.', $start);
				$end2 = str_replace('-', '.', $end);
			?>

			<?php
			$now = new DateTime();
			$end = new DateTime('2026-10-07T11:47');
			$is_active = $now <= $end;
			if (!$is_active): ?>
				<div class="error-message">
					Опрос завершён
				</div>
			<?php endif; ?>

        
            <div class="poll-period" style="text-align: right; margin-bottom: 15px;">
                <strong style="color: #4683b7; border-bottom: 1px dotted #1976D2;">
                    Опрос действует с <?= htmlspecialchars($start2)?> по <?= htmlspecialchars($end2)?>
                </strong>
            </div>

            <form action="../polls_vote.php" method="post">
                <input type="hidden" name="poll_id" value="1788771167">
            <div class="question">
                <h3><strong>1.	В течение какого срока после увольнения с государственной службы действует ограничение на трудоустройство госслужащего:</strong></h3>

                <label><input type="radio" name="answer[0]" value="0"> <span >3 месяца</span></label><br>

                <label><input type="radio" name="answer[0]" value="1"> <span >6 месяцев</span></label><br>

                <label><input type="radio" name="answer[0]" value="2"> <span >2 года</span></label><br>
            </div>

            <div class="question">
                <h3><strong>2. При каких условиях на трудоустройство бывшего госслужащего нужно согласие соответствующей комиссии по соблюдению требований к служебному поведению государственных служащих и урегулированию конфликта интересов</strong></h3>
                <label><input type="checkbox" name="answer[1][]" value="0"> <span>бывший государственный служащий перед увольнением являлся членом комиссии по соблюдению требований к служебному поведению государственных служащих и урегулированию конфликта интересов</span></label><br>
                <label><input type="checkbox" name="answer[1][]" value="1"> <span>отдельные функции государственного (административного) управления организацией, в которую планируется трудоустройство, входили в должностные обязанности государственного служащего</span></label><br>
                <label><input type="checkbox" name="answer[1][]" value="2"> <span>бывший государственный служащий зарегистрировался в качестве индивидуального предпринимателя</span></label><br>
                <label><input type="checkbox" name="answer[1][]" value="3"> <span>должность гражданской службы, замещаемая государственным служащим до увольнения включена в Перечень должностей</span></label><br>
            </div>

            <div class="question">
                <h3><strong>3. Какой работодатель не обязан сообщать представителю нанимателя о заключении трудового договора с бывшим госслужащим:</strong></h3>

                <label><input type="radio" name="answer[2]" value="0"> <span >коммерческая организация</span></label><br>

                <label><input type="radio" name="answer[2]" value="1"> <span >некоммерческая организация</span></label><br>

                <label><input type="radio" name="answer[2]" value="2"> <span >государственная корпорация</span></label><br>

                <label><input type="radio" name="answer[2]" value="3"> <span >государственный (муниципальный) орган</span></label><br>

                <label><input type="radio" name="answer[2]" value="4"> <span >индивидуальный предприниматель</span></label><br>
            </div>

            <div class="question">
                <h3><strong>4. Госслужащему, планирующему после увольнения с государственной службы трудоустройство в организацию, в отношении которой он осуществляет отдельные функции государственного управления, необходимо</strong></h3>
                <label><input type="checkbox" name="answer[3][]" value="0"> <span>воздержаться от переговоров о трудоустройстве до момента увольнения с государственной службы</span></label><br>
                <label><input type="checkbox" name="answer[3][]" value="1"> <span>подать обращение о даче согласия на замещение должности в организации</span></label><br>
                <label><input type="checkbox" name="answer[3][]" value="2"> <span>сообщить в устной форме начальнику своего отдела о намерении трудоустроиться в организацию</span></label><br>
            </div>

            <div class="question">
                <h3><strong>5. Последствия заключения трудового договора с организацией, в отношении которой госслужащий осуществлял отдельные функции государственного управления без согласия соответствующей комиссии по соблюдению требований к служебному поведению государственных служащих и урегулированию конфликта интересов</strong></h3>
                <label><input type="checkbox" name="answer[4][]" value="0"> <span>работодатель будет оштрафован</span></label><br>
                <label><input type="checkbox" name="answer[4][]" value="1"> <span>договор уже заключен и остается действительным</span></label><br>
                <label><input type="checkbox" name="answer[4][]" value="2"> <span>договор будет считаться заключенным с нарушением</span></label><br>
            </div>

            <button type="button" onclick="submitVote()" <?= !$is_active ? 'disabled' : '' ?>>
                Проголосовать
            </button>
        </form>

        <hr>
        
        <span id="poll-actions">
                <a href="#" onclick="unvote(); return false;">  Отменить голос</a>
            <?php 
            $user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'unknown';
            $user = strtoupper(trim($user));
            
            $supervisors = ['FSFK\SoldatovAN', 'FSFK\SenichkinOV', 'FSFK\ChernikovAV']; 
            $supervisorsUpCase = array_map('strtoupper', $supervisors);
            
            $managers = ['FSFK\VasinAN','FSFK\AnisimovaAA','FSFK\BalyberdinaNV','FSFK\RyzhovaNN','FSFK\SenichkinaOI','FSFK\HvostenkoNP','FSFK\GlazkovaOG','FSFK\KozlovaOA','FSFK\KostylevaOV','FSFK\AnikinaLN','FSFK\ErmakovaNA','FSFK\VasilevaEV','FSFK\ArtamonovaLL','FSFK\BubnovaVL','FSFK\AndryuhinaTG','FSFK\DvoryanchikovaTN','FSFK\DocenkoDV','FSFK\SeynerOI','FSFK\BelovaOYU','FSFK\KravchenkoNV','FSFK\BoyarovaPA','FSFK\KorostelevIA','FSFK\ZharkovaYUI','FSFK\DmitrievaSV','FSFK\ProdanPV','FSFK\SvechnikovaEV','FSFK\ChistyakovaVV','FSFK\ArseevaEYU','FSFK\NosovaOV','FSFK\ChuikinVYU','FSFK\ZaharovAV','FSFK\AndrianovaEYU','FSFK\KizinaNV','FSFK\PlatonovVA','FSFK\FinakinaEYU','FSFK\MedvedevaVS','FSFK\GrebennikovVA','FSFK\GorbachovaNV','FSFK\AleshinaOV','FSFK\KolyazinaEYU'];  
            $managersUpCase = array_map('strtoupper', $managers);
            
            if (in_array($user, $supervisorsUpCase, true) || $user === 'FSFK\MARKUSHOVAKV') {
                echo ' | <a href="../polls_results.php?id=1788771167">Посмотреть результаты</a>';
                echo ' | <a href="mailto:?subject=%D0%9E%D0%BF%D1%80%D0%BE%D1%81%20%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%20%D0%BE%D0%B1%D1%83%D1%87%D0%B5%D0%BD%D0%B8%D1%8F%20%D1%82%D1%80%D1%83%D0%B4%D0%BE%D1%83%D1%81%D1%82%D1%80%D0%BE%D0%B9%D1%81%D1%82%D0%B2%D0%BE%20%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%20%D1%83%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D0%B5%D0%BD%D0%B8%D1%8F%2C%20%D0%B4%D0%B0%D1%87%D0%B0%20%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%81%D0%B8%D1%8F%20%D0%B7%D0%B0%20%D0%B7%D0%B0%D0%BC%D0%B5%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B4%D0%BE%D0%BB%D0%B6%D0%BD%D0%BE%D1%81%D1%82%D0%B8&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1788771167.php">Поделиться</a>';
                echo ' | <a href="#" onclick="changePoll(); return false;">Редактировать опрос</a>';
                echo ' | <a href="#" style="color:red" onclick="deletePoll(); return false;">Удалить опрос</a>';
            } elseif (in_array($user, $managersUpCase, true)) {
                echo ' | <a href="../polls_results.php?id=1788771167">Посмотреть результаты</a>';
                echo ' | <a href="mailto:?subject=%D0%9E%D0%BF%D1%80%D0%BE%D1%81%20%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%20%D0%BE%D0%B1%D1%83%D1%87%D0%B5%D0%BD%D0%B8%D1%8F%20%D1%82%D1%80%D1%83%D0%B4%D0%BE%D1%83%D1%81%D1%82%D1%80%D0%BE%D0%B9%D1%81%D1%82%D0%B2%D0%BE%20%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%20%D1%83%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D0%B5%D0%BD%D0%B8%D1%8F%2C%20%D0%B4%D0%B0%D1%87%D0%B0%20%D1%81%D0%BE%D0%B3%D0%BB%D0%B0%D1%81%D0%B8%D1%8F%20%D0%B7%D0%B0%20%D0%B7%D0%B0%D0%BC%D0%B5%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B4%D0%BE%D0%BB%D0%B6%D0%BD%D0%BE%D1%81%D1%82%D0%B8&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1788771167.php">Поделиться</a>';
            }               
            ?>
        </span>
    </div>
    <?php footer($access, $db); ?>
	
	<script>
        const POLL_ID = '1788771167';
        const POLL_END_DATE = '2026-10-07T11:47'; // формат: 2025-04-10T18:00

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
            // Проверка: на каждый вопрос с типом radio или checkbox должен быть выбран хотя бы один ответ
            let allQuestionsAnswered = true;
            const questions = document.querySelectorAll('.question');
            
            questions.forEach(question => {
                const radios = question.querySelectorAll('input[type="radio"]');
                const checkboxes = question.querySelectorAll('input[type="checkbox"]');
                
                let answered = false;
                if (radios.length > 0) {
                    answered = Array.from(radios).some(radio => radio.checked);
                } else if (checkboxes.length > 0) {
                    answered = Array.from(checkboxes).some(checkbox => checkbox.checked);
                }
                
                if (!answered) {
                    allQuestionsAnswered = false;
                }
            });
            
            if (!allQuestionsAnswered) {
                alert('Пожалуйста, ответьте на все вопросы, выбрав хотя бы один вариант в каждом.');
                return;
            }
            
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
            if (!isPollActive()) {
                alert('Нельзя отменить голос, так как опрос уже завершён.');
                return;
            }
            
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
		
		function changePoll() {
            if (!confirm("Вы уверены, что хотите редактировать этот опрос?")) return;
            window.location.href = `../polls_change_form.php?id=1788771167`;
        }
    </script>
</body>
</html>