<?php
include 'common.php';

$poll_id = $_GET['id'] ?? null;
if (!$poll_id) {
    die("ID опроса не указан");
}

$poll_file = __DIR__ . "/polls/{$poll_id}.json";
if (!file_exists($poll_file)) {
    die("Опрос не найден");
}

$poll = json_decode(file_get_contents($poll_file), true);
if (!$poll) {
    die("Ошибка чтения данных опроса");
}

$user = $_SERVER['AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? '';
if (empty($user) || (strtoupper(trim($user)) !== strtoupper($poll['creator']) 
    && strtoupper(trim($user)) !== "FSFK\\SOLDATOVAN" 
    && strtoupper(trim($user)) !== "FSFK\\SENICHKINOV"
	&& strtoupper(trim($user)) !== "FSFK\\CHERNIKOVAV")) {
    die("У вас нет прав на редактирование этого опроса");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <title>Редактирование опроса</title>
    <link rel="stylesheet" type="text/css" href="css/styles_v20221116.css" />
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        #main {
            max-width: 700px;
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
			border-left: 4px solid #e74c3c;
			border-top: 4px solid #e74c3c;
        }

        h1::before {
            content: "📝";
            font-size: 1.3em;
        }

        h3 {
            color: #34495e;
            font-size: 1.15rem;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
            margin-top: 30px;
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #34495e;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #dce1e8;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
            outline: none;
        }

        .question-block {
            border: 1px solid #e0e5ec;
            padding: 20px;
            margin-bottom: 24px;
            border-radius: 10px;
            background: #fafbfc;
        }

        .question-block h4 {
            margin: 0 0 15px 0;
            color: #2980b9;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .answer-block {
            margin-top: 12px;
            margin-left: 20px;
        }

        .answer-block div {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 16px;
            margin-right: 8px;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn:disabled {
            background: #bdc3c7 !important;
            cursor: not-allowed;
            transform: none;
        }

        .end-date-warning {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 4px;
            display: none;
            align-items: center;
            gap: 5px;
        }

        .end-date-warning::before {
            content: "⏳";
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
		
		.form_footer a, a:visited {
			color: white;
		}
    </style>
</head>
<body>
    <?php include 'print_header.php'; ?>
    <div id="main">
        <h1>Редактирование опроса</h1>
        <form id="pollForm" method="post" action="polls_change.php">
            <input type="hidden" name="poll_id" value="<?= htmlspecialchars($poll_id) ?>" />
            
            <div class="form-group">
                <label>Название опроса</label>
                <input type="text" name="title" value="<?= htmlspecialchars($poll['title']) ?>" required maxlength="190" />
            </div>
            
            <div class="form-group">
                <label>Дата начала</label>
                <input type="datetime-local" name="start_date" value="<?= htmlspecialchars(str_replace(' ', 'T', $poll['start_date'])) ?>" required />
            </div>
            
            <div class="form-group">
                <label>Дата окончания</label>
                <input 
                    type="datetime-local" 
                    name="end_date" 
                    id="end_date" 
                    value="<?= htmlspecialchars(str_replace(' ', 'T', $poll['end_date'])) ?>" 
                    required 
                />
                <div class="end-date-warning" id="end_date_warning">
                    Срок окончения уже наступил
                </div>
            </div>
            
            <div class="form-group">
                <label>Тип голосования</label>
                <select name="voting_type">
                    <option value="public" <?= $poll['voting_type'] === 'public' ? 'selected' : '' ?>>Публичное</option>
                    <option value="anonymous" <?= $poll['voting_type'] === 'anonymous' ? 'selected' : '' ?>>Анонимное</option>
                </select>
            </div>
            
            <h3>Вопросы</h3>
            <div id="questions">
                <?php foreach ($poll['questions'] as $qi => $q): ?>
                <div class="question-block">
                    <h4>Вопрос <?= $qi + 1 ?></h4>
                    <div class="form-group">
                        <label>Текст вопроса</label>
                        <input type="text" name="questions[]" value="<?= htmlspecialchars($q['text']) ?>" required />
                    </div>
                    
                    <div class="form-group">
                        <label>Тип ответа</label>
                        <select name="type[]">
                            <option value="single" <?= $q['type'] === 'single' ? 'selected' : '' ?>>Один ответ</option>
                            <option value="multiple" <?= $q['type'] === 'multiple' ? 'selected' : '' ?>>Несколько ответов</option>
                        </select>
                    </div>
                    
                    <div class="answer-block">
                        <label>Варианты ответов</label>
                        <?php foreach ($q['answers'] as $ai => $a): ?>
                        <div>
                            <input type="text" name="answers[<?= $qi ?>][]" value="<?= htmlspecialchars($a) ?>" required style="flex: 1;" />
                            <button type="button" class="btn btn-danger remove-answer">Удалить</button>
                        </div>
                        <?php endforeach; ?>
                        <button type="button" class="btn btn-primary add-answer">+ Добавить вариант</button>
                    </div>
					
					<div style="display: none;">
                        <?php foreach ($q['votes'] as $ai => $a): ?>
                        <div>
                            <input type="text" name="votes[<?= $qi ?>][]" value="<?= htmlspecialchars($a) ?>" required style="flex: 1;" />
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="margin-top: 16px;">
                        <button type="button" class="btn btn-danger remove-question">Удалить вопрос</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button type="button" id="add-question" class="btn btn-primary">+ Добавить вопрос</button>
            
            <div class="form-footer">
                <a href="polls/<?= $poll_id ?>.php" class="btn btn-danger">Отмена</a>
                <button type="submit" class="btn btn-primary" id="save_btn" disabled>Сохранить изменения</button>
            </div>
        </form>
    </div>

    <script>
    // Проверка даты окончания
    const endDateInput = document.getElementById('end_date');
    const saveBtn = document.getElementById('save_btn');
    const endWarning = document.getElementById('end_date_warning');

    function checkEndDate() {
        const now = new Date();
        const end = new Date(endDateInput.value);
        if (isNaN(end.getTime())) {
            saveBtn.disabled = true;
            endWarning.style.display = 'none';
            return;
        }
        
        if (end < now) {
            saveBtn.disabled = true;
            endWarning.style.display = 'flex';
        } else {
            saveBtn.disabled = false;
            endWarning.style.display = 'none';
        }
    }

    // Выполняем при загрузке и при изменении поля
    checkEndDate();
    endDateInput.addEventListener('input', checkEndDate);

    // Логика добавления/удаления вопросов и ответов (сохранена)
    document.getElementById('add-question').onclick = function() {
        const index = document.querySelectorAll('.question-block').length;
        const q = document.createElement('div');
        q.className = 'question-block';
        q.innerHTML = `
            <h4>Вопрос ${index + 1}</h4>
            <div class="form-group">
                <label>Текст вопроса</label>
                <input type="text" name="questions[]" placeholder="Введите вопрос" required />
            </div>
            
            <div class="form-group">
                <label>Тип ответа</label>
                <select name="type[]">
                    <option value="single">Один ответ</option>
                    <option value="multiple">Несколько ответов</option>
                </select>
            </div>
            
            <div class="answer-block">
                <label>Варианты ответов</label>
                <div>
                    <input type="text" name="answers[${index}][]" placeholder="Вариант 1" required style="flex: 1;" />
                    <button type="button" class="btn btn-danger remove-answer">Удалить</button>
                </div>
                <div>
                    <input type="text" name="answers[${index}][]" placeholder="Вариант 2" required style="flex: 1;" />
                    <button type="button" class="btn btn-danger remove-answer">Удалить</button>
                </div>
                <button type="button" class="btn btn-primary add-answer">+ Добавить вариант</button>
            </div>
            
            <div style="margin-top: 16px;">
                <button type="button" class="btn btn-danger remove-question">Удалить вопрос</button>
            </div>
        `;
        document.getElementById('questions').appendChild(q);
    };

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-answer')) {
            const answerBlock = e.target.closest('.answer-block');
            const questionIndex = Array.from(document.querySelectorAll('.question-block')).indexOf(e.target.closest('.question-block'));
            const inputs = answerBlock.querySelectorAll('input[type="text"]');
            const nextIndex = inputs.length;
            
            const newDiv = document.createElement('div');
            newDiv.style.cssText = 'display: flex; gap: 8px; margin-bottom: 10px;';
            newDiv.innerHTML = `
                <input type="text" name="answers[${questionIndex}][]" placeholder="Вариант ${nextIndex + 1}" required style="flex: 1;" />
                <button type="button" class="btn btn-danger remove-answer">Удалить</button>
            `;
            answerBlock.insertBefore(newDiv, e.target);
        }
        
        if (e.target.classList.contains('remove-answer')) {
            const answerBlock = e.target.closest('.answer-block');
            if (answerBlock.querySelectorAll('input[type="text"]').length > 2) {
                if (confirm('Удалить этот вариант ответа?')) {
                    e.target.closest('div').remove();
                }
            } else {
                alert('Нельзя удалить менее двух вариантов ответа.');
            }
        }
        
        if (e.target.classList.contains('remove-question')) {
            const questions = document.querySelectorAll('.question-block');
            if (questions.length > 1) {
                if (confirm('Удалить этот вопрос?')) {
                    e.target.closest('.question-block').remove();
                }
            } else {
                alert('Нельзя удалить последний вопрос.');
            }
        }
    });
    </script>
</body>
</html>