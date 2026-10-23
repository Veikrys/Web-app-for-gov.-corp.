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
    <title>Осуществление контроля за соответствием расходов федеральных государственных гражданских служащих и работников федерального казенного учреждения «Центр по обеспечению деятельности Казначейства России» их доходам</title>
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
			<h1>Осуществление контроля за соответствием расходов федеральных государственных гражданских служащих и работников федерального казенного учреждения «Центр по обеспечению деятельности Казначейства России» их доходам</h1>
			<?php
				$start = str_replace('T', ' ', '2026-06-17T11:36');
				$end = str_replace('T', ' ', '2026-09-16T17:15');
				
				$start2 = str_replace('-', '.', $start);
				$end2 = str_replace('-', '.', $end);
			?>

			<?php
			$now = new DateTime();
			$end = new DateTime('2026-09-16T17:15');
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
                <input type="hidden" name="poll_id" value="1781694962">
            <div class="question">
                <h3><strong>Сведения о расходах не представляются при приобретении:</strong></h3>

                <label><input type="radio" name="answer[0]" value="0"> <span >объекта недвижимости</span></label><br>

                <label><input type="radio" name="answer[0]" value="1"> <span >транспортного средства</span></label><br>

                <label><input type="radio" name="answer[0]" value="2"> <span >ценных бумаг, акций (долей участия, паев в уставных (складочных) капиталах организаций)</span></label><br>

                <label><input type="radio" name="answer[0]" value="3"> <span >ювелирных украшений</span></label><br>

                <label><input type="radio" name="answer[0]" value="4"> <span >цифровых финансовых активов, цифровой валюты</span></label><br>
            </div>

            <div class="question">
                <h3><strong>Сведения о расходах декларируются в случае, если общая сумма сделок, совершенных в отчетном периоде превышает:</strong></h3>

                <label><input type="radio" name="answer[1]" value="0"> <span >общий доход лица, его супруги (супруга) и несовершеннолетних детей за три последних года, предшествующих отчетному периоду</span></label><br>

                <label><input type="radio" name="answer[1]" value="1"> <span >доход лица за три последних года, предшествующих отчетному периоду</span></label><br>

                <label><input type="radio" name="answer[1]" value="2"> <span >общий доход лица, его супруги (супруга) за три последних года, предшествующих отчетному периоду</span></label><br>
            </div>

            <div class="question">
                <h3><strong>Сведения о расходах указываются в случае:</strong></h3>

                <label><input type="radio" name="answer[2]" value="0"> <span >возведения жилого дома на земельном участке</span></label><br>

                <label><input type="radio" name="answer[2]" value="1"> <span >наследования объекта недвижимости</span></label><br>

                <label><input type="radio" name="answer[2]" value="2"> <span >дарения объекта недвижимости</span></label><br>

                <label><input type="radio" name="answer[2]" value="3"> <span >приобретения по договору долевого строительства квартиры</span></label><br>
            </div>

            <div class="question">
                <h3><strong>Контроль за расходами органами прокуратуры Российской Федерации в отношении бывших гражданских служащих (работников):</strong></h3>

                <label><input type="radio" name="answer[3]" value="0"> <span >может проводиться</span></label><br>

                <label><input type="radio" name="answer[3]" value="1"> <span >не может проводиться</span></label><br>
            </div>

            <div class="question">
                <h3><strong>Выберите все верные утверждения:</strong></h3>
                <label><input type="checkbox" name="answer[4][]" value="0"> <span>обращение в доход Российской Федерации возможно в случае если в отношении имущества государственным служащим не представлено сведений, подтверждающих их приобретение на законные доходы</span></label><br>
                <label><input type="checkbox" name="answer[4][]" value="1"> <span>обращение в доход Российской Федерации возможно при непредставлении сведений в отношении части приобретенного имущества</span></label><br>
                <label><input type="checkbox" name="answer[4][]" value="2"> <span>обращение в доход Российской Федерации частной собственности невозможно</span></label><br>
            </div>

            <div class="question">
                <h3><strong>Для расчета общего дохода доход супруги (супруга) нужно учитывать:</strong></h3>

                <label><input type="radio" name="answer[5]" value="0"> <span >если государственный служащий состоял в браке в момент сделки</span></label><br>

                <label><input type="radio" name="answer[5]" value="1"> <span >только если государственный служащий состоял в браке в момент сделки и в течение трех лет до года совершения сделки</span></label><br>

                <label><input type="radio" name="answer[5]" value="2"> <span >если государственный служащий состоял в браке на отчетную дату</span></label><br>
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
                echo ' | <a href="../polls_results.php?id=1781694962">Посмотреть результаты</a>';
                echo ' | <a href="mailto:?subject=%D0%9E%D1%81%D1%83%D1%89%D0%B5%D1%81%D1%82%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BA%D0%BE%D0%BD%D1%82%D1%80%D0%BE%D0%BB%D1%8F%20%D0%B7%D0%B0%20%D1%81%D0%BE%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D1%81%D1%82%D0%B2%D0%B8%D0%B5%D0%BC%20%D1%80%D0%B0%D1%81%D1%85%D0%BE%D0%B4%D0%BE%D0%B2%20%D1%84%D0%B5%D0%B4%D0%B5%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D1%85%20%D0%B3%D0%BE%D1%81%D1%83%D0%B4%D0%B0%D1%80%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D1%8B%D1%85%20%D0%B3%D1%80%D0%B0%D0%B6%D0%B4%D0%B0%D0%BD%D1%81%D0%BA%D0%B8%D1%85%20%D1%81%D0%BB%D1%83%D0%B6%D0%B0%D1%89%D0%B8%D1%85%20%D0%B8%20%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BD%D0%B8%D0%BA%D0%BE%D0%B2%20%D1%84%D0%B5%D0%B4%D0%B5%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B3%D0%BE%20%D0%BA%D0%B0%D0%B7%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE%20%D1%83%D1%87%D1%80%D0%B5%D0%B6%D0%B4%D0%B5%D0%BD%D0%B8%D1%8F%20%C2%AB%D0%A6%D0%B5%D0%BD%D1%82%D1%80%20%D0%BF%D0%BE%20%D0%BE%D0%B1%D0%B5%D1%81%D0%BF%D0%B5%D1%87%D0%B5%D0%BD%D0%B8%D1%8E%20%D0%B4%D0%B5%D1%8F%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8%20%D0%9A%D0%B0%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%B9%D1%81%D1%82%D0%B2%D0%B0%20%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D0%B8%C2%BB%20%D0%B8%D1%85%20%D0%B4%D0%BE%D1%85%D0%BE%D0%B4%D0%B0%D0%BC&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1781694962.php">Поделиться</a>';
                echo ' | <a href="#" onclick="changePoll(); return false;">Редактировать опрос</a>';
                echo ' | <a href="#" style="color:red" onclick="deletePoll(); return false;">Удалить опрос</a>';
            } elseif (in_array($user, $managersUpCase, true)) {
                echo ' | <a href="../polls_results.php?id=1781694962">Посмотреть результаты</a>';
                echo ' | <a href="mailto:?subject=%D0%9E%D1%81%D1%83%D1%89%D0%B5%D1%81%D1%82%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BA%D0%BE%D0%BD%D1%82%D1%80%D0%BE%D0%BB%D1%8F%20%D0%B7%D0%B0%20%D1%81%D0%BE%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D1%81%D1%82%D0%B2%D0%B8%D0%B5%D0%BC%20%D1%80%D0%B0%D1%81%D1%85%D0%BE%D0%B4%D0%BE%D0%B2%20%D1%84%D0%B5%D0%B4%D0%B5%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D1%85%20%D0%B3%D0%BE%D1%81%D1%83%D0%B4%D0%B0%D1%80%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D1%8B%D1%85%20%D0%B3%D1%80%D0%B0%D0%B6%D0%B4%D0%B0%D0%BD%D1%81%D0%BA%D0%B8%D1%85%20%D1%81%D0%BB%D1%83%D0%B6%D0%B0%D1%89%D0%B8%D1%85%20%D0%B8%20%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%BD%D0%B8%D0%BA%D0%BE%D0%B2%20%D1%84%D0%B5%D0%B4%D0%B5%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B3%D0%BE%20%D0%BA%D0%B0%D0%B7%D0%B5%D0%BD%D0%BD%D0%BE%D0%B3%D0%BE%20%D1%83%D1%87%D1%80%D0%B5%D0%B6%D0%B4%D0%B5%D0%BD%D0%B8%D1%8F%20%C2%AB%D0%A6%D0%B5%D0%BD%D1%82%D1%80%20%D0%BF%D0%BE%20%D0%BE%D0%B1%D0%B5%D1%81%D0%BF%D0%B5%D1%87%D0%B5%D0%BD%D0%B8%D1%8E%20%D0%B4%D0%B5%D1%8F%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D0%B8%20%D0%9A%D0%B0%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%B9%D1%81%D1%82%D0%B2%D0%B0%20%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D0%B8%C2%BB%20%D0%B8%D1%85%20%D0%B4%D0%BE%D1%85%D0%BE%D0%B4%D0%B0%D0%BC&amp;body=%D0%9F%D1%80%D0%BE%D0%B9%D0%B4%D0%B8%D1%82%D0%B5%20%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3A%20https%3A%2F%2Fs3700web01.fsfk.local%2Fpolls%2F1781694962.php">Поделиться</a>';
            }               
            ?>
        </span>
    </div>
    <?php footer($access, $db); ?>
	
	<script>
        const POLL_ID = '1781694962';
        const POLL_END_DATE = '2026-09-16T17:15'; // формат: 2025-04-10T18:00

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
            window.location.href = `../polls_change_form.php?id=1781694962`;
        }
    </script>
</body>
</html>