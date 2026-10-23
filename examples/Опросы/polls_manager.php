<?php 
include 'common.php'; 
// $access=ip_access($db);
// add_rec($access,$db);
// page_access($access,$db);
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
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
	<title>Менеджер опросов</title>
	<link rel="stylesheet" type="text/css" media="all" href="css/menu_v20130613.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles_v20221116.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/forms_v20150107.css" />
	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
	<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
	<script type="text/javascript" language="javascript" src="js/jquery_v20130528.js"></script>
	<script type="text/javascript" language="javascript" src="js/menu_v20130603.js" defer="defer"></script>
</head>
<body>
    <?php print_header($access, $db); ?>
    <div id="main">
        <h2><span>Менеджер опросов</span></h2>

        <form action="polls_save.php" method="post">
            <div style="max-width: 600px; margin: 0 auto;">
                <table class="frm_l" width="100%" style="background-position: left top; background-size: cover; table-layout: fixed; margin-top: 55px; border-collapse: separate; padding-top: 15px; padding-left: 15px;">
					<tr>
						<td style="width: 30%; white-space: nowrap;">Название опроса:</td>
						<td style="width: 70%;"><input type="text" name="title" required style="width: 100%; max-width: 100%; box-sizing: border-box;" maxlength="190"></td>
					</tr>
					<tr style="display: none;">
						<td>Дата начала:</td>
						<td><input type="datetime-local" name="start_date" required style="width: 100%; box-sizing: border-box;" value="<?= date('Y-m-d\TH:i') ?>"></td>
					</tr>
					<tr>
						<td>Дата окончания:</td>
						<td><input type="datetime-local" name="end_date" required style="width: 100%; box-sizing: border-box;"></td>
					</tr>
					<tr>
						<td>Выберите тип опроса:</td>
						<td>
							<div>
								<input type="radio" name="voting_type" value="public" checked>
								<label>Публичное голосование</label>
							</div>
							
							<div>
								<input type="radio" name="voting_type" value="anonymous">
								<label>Анонимное голосование</label>
							</div>
						</td>
					</tr>
                </table>

                <h3>Вопросы</h3>
                <div id="questions" style="margin-bottom: 10px;">
                    <div class="question-block" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                        <input type="text" name="questions[]" placeholder="Вопрос" required style="width: 100%; padding: 8px; margin-bottom: 8px; box-sizing: border-box;">
                        <select name="type[]" style="padding: 6px; margin-bottom: 8px; width: 100%; box-sizing: border-box;">
                            <option value="single">Один ответ</option>
                            <option value="multiple">Несколько ответов</option>
                        </select>
                        <div class="answers" style="margin-left: 20px; margin-bottom: 8px;">
                            <input type="text" name="answers[0][]" placeholder="Вариант 1" required style="width: 100%; padding: 6px; margin-bottom: 4px; box-sizing: border-box;">
                            <input type="text" name="answers[0][]" placeholder="Вариант 2" required style="width: 100%; padding: 6px; margin-bottom: 4px; box-sizing: border-box;">
                        </div>
                        <button type="button" class="add-answer" style="padding: 4px 8px; font-size: 12px;">+ Добавить вариант</button>
                    </div>
                </div>
                <button type="button" id="add-question" style="padding: 6px 12px; font-size: 14px; margin-bottom: 15px;">+ Добавить вопрос</button><br>
                <button type="submit" style="margin-bottom: 24px; padding: 10px 20px; font-size: 16px; background: #4683b7; color: white; border: none; border-radius: 4px; cursor: pointer;">Создать опрос</button>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('add-question').onclick = function() {
        const index = document.querySelectorAll('.question-block').length;
        const q = document.createElement('div');
        q.className = 'question-block';
        q.style.cssText = 'margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;';
        q.innerHTML = `
            <button type="button" class="remove-question" style="position: relative; left: 560px; width: 20px; height: 20px; background: #ff4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; line-height: 1;">х</button>
			<input type="text" name="questions[]" placeholder="Вопрос" required style="width: 100%; padding: 8px; margin-bottom: 8px; margin-top: 10px; box-sizing: border-box;">
			<select name="type[]" style="padding: 6px; margin-bottom: 8px; width: 100%; box-sizing: border-box;">
                <option value="single">Один ответ</option>
                <option value="multiple">Несколько ответов</option>
            </select>
            <div class="answers" style="margin-left: 20px; margin-bottom: 8px;">
                <input type="text" name="answers[${index}][]" placeholder="Вариант 1" required style="width: 100%; padding: 6px; margin-bottom: 4px; box-sizing: border-box;">
                <input type="text" name="answers[${index}][]" placeholder="Вариант 2" required style="width: 100%; padding: 6px; margin-bottom: 4px; box-sizing: border-box;">
            </div>
            <button type="button" class="add-answer" style="padding: 4px 8px; font-size: 12px;">+ Добавить вариант</button>
        `;
        document.getElementById('questions').appendChild(q);
    };

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-answer')) {
            const block = e.target.closest('.question-block');
            const answersDiv = e.target.previousElementSibling;
            const inputs = answersDiv.querySelectorAll('input');
            const questionIndex = Array.from(document.querySelectorAll('.question-block')).indexOf(block);
            const nextIndex = inputs.length;

            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = `answers[${questionIndex}][${nextIndex}]`;
            newInput.placeholder = `Вариант ${nextIndex + 1}`;
            newInput.required = true;
            newInput.style.cssText = 'width: 100%; padding: 6px; margin-bottom: 4px; box-sizing: border-box;';

            const newBtn = document.createElement('button');
            newBtn.type = 'button';
            newBtn.className = 'remove-answer';
            newBtn.style.cssText = 'margin-left: 6px; width: 20px; height: 20px; background: #999; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; line-height: 1;';
            newBtn.textContent = 'х';

            const newDiv = document.createElement('div');
            newDiv.style.cssText = 'position: relative; display: flex; align-items: center; margin-bottom: 4px;';

            newDiv.appendChild(newInput);
            newDiv.appendChild(newBtn);

            answersDiv.appendChild(newDiv);

        }
    });

    // Удаление вопроса
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            if (document.querySelectorAll('.question-block').length > 1) {
                if (confirm('Вы уверены, что хотите удалить этот вопрос?')) {
                    e.target.closest('.question-block').remove();
                }
            } else {
                alert('Нельзя удалить последний вопрос.');
            }
        }
    });

    // Удаление варианта ответа
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-answer')) {
            const answersDiv = e.target.closest('.answers');
            if (answersDiv.querySelectorAll('input').length > 2) {
                if (confirm('Вы уверены, что хотите удалить этот вариант ответа?')) {
                    e.target.closest('div[style*=\"position: relative\"]').remove();
                }
            } else {
                alert('Нельзя удалить менее двух вариантов ответа.');
            }
        }
    });
</script>
		<?php footer($access, $db); ?>
</body>
</html>