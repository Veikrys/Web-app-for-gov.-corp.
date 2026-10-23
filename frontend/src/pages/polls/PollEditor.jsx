import { useState } from 'react';

let uid = 0;
const nextKey = () => ++uid;

const newAnswer = (text = '') => ({ key: nextKey(), text });
const newQuestion = (q = {}) => ({
  key: nextKey(),
  origIndex: q.origIndex,
  text: q.text || '',
  type: q.type || 'single',
  answers: (q.answers?.length ? q.answers : ['', '']).map(newAnswer),
});

// Форма опроса: используется и для создания, и для редактирования
export default function PollEditor({ initial, withStartDate = false, submitLabel, onSubmit, onCancel }) {
  const [title, setTitle] = useState(initial?.title || '');
  const [startDate, setStartDate] = useState(initial?.start_date || '');
  const [endDate, setEndDate] = useState(initial?.end_date || '');
  const [votingType, setVotingType] = useState(initial?.voting_type || 'public');
  const [questions, setQuestions] = useState(() => (
    initial?.questions?.length ? initial.questions.map(newQuestion) : [newQuestion()]
  ));
  const [saving, setSaving] = useState(false);

  // Редактировать с прошедшей датой окончания нельзя
  const endPassed = withStartDate && endDate && new Date(endDate) < new Date();

  const updateQuestion = (key, patch) => setQuestions((qs) => qs.map((q) => (q.key === key ? { ...q, ...patch } : q)));

  const removeQuestion = (key) => {
    if (!window.confirm('Удалить этот вопрос?')) return;
    setQuestions((qs) => qs.filter((q) => q.key !== key));
  };

  const addAnswer = (key) => setQuestions((qs) => qs.map((q) => (
    q.key === key ? { ...q, answers: [...q.answers, newAnswer()] } : q
  )));

  const updateAnswer = (qKey, aKey, text) => setQuestions((qs) => qs.map((q) => (
    q.key === qKey ? { ...q, answers: q.answers.map((a) => (a.key === aKey ? { ...a, text } : a)) } : q
  )));

  const removeAnswer = (qKey, aKey) => {
    if (!window.confirm('Удалить этот вариант ответа?')) return;
    setQuestions((qs) => qs.map((q) => (
      q.key === qKey ? { ...q, answers: q.answers.filter((a) => a.key !== aKey) } : q
    )));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      await onSubmit({
        title,
        ...(withStartDate ? { start_date: startDate } : {}),
        end_date: endDate,
        voting_type: votingType,
        questions: questions.map((q) => ({
          origIndex: q.origIndex,
          text: q.text,
          type: q.type,
          answers: q.answers.map((a) => a.text),
        })),
      });
    } finally {
      setSaving(false);
    }
  };

  return (
    <form className="polls-form" onSubmit={handleSubmit}>
      <div className="polls-card">
        <div className="polls-field">
          <label htmlFor="poll-title">Название опроса</label>
          <input
            id="poll-title"
            type="text"
            value={title}
            maxLength={190}
            required
            onChange={(e) => setTitle(e.target.value)}
          />
        </div>

        {withStartDate && (
          <div className="polls-field">
            <label htmlFor="poll-start">Дата начала</label>
            <input
              id="poll-start"
              type="datetime-local"
              value={startDate}
              required
              onChange={(e) => setStartDate(e.target.value)}
            />
          </div>
        )}

        <div className="polls-field">
          <label htmlFor="poll-end">Дата окончания</label>
          <input
            id="poll-end"
            type="datetime-local"
            value={endDate}
            required
            onChange={(e) => setEndDate(e.target.value)}
          />
          {endPassed && <div className="polls-field-warning">⏳ Срок окончания уже наступил</div>}
        </div>

        <div className="polls-field">
          <label>Тип голосования</label>
          <div className="polls-radio-group">
            <label className="polls-radio">
              <input
                type="radio"
                name="voting_type"
                checked={votingType === 'public'}
                onChange={() => setVotingType('public')}
              />
              <span>Публичное голосование</span>
            </label>
            <label className="polls-radio">
              <input
                type="radio"
                name="voting_type"
                checked={votingType === 'anonymous'}
                onChange={() => setVotingType('anonymous')}
              />
              <span>Анонимное голосование</span>
            </label>
          </div>
        </div>
      </div>

      <h3 className="polls-subtitle">Вопросы</h3>

      {questions.map((q, qi) => (
        <div className="polls-question-card" key={q.key}>
          <div className="polls-question-head">
            <span className="polls-question-num">Вопрос {qi + 1}</span>
            <button
              type="button"
              className="polls-btn polls-btn-danger polls-btn-sm"
              onClick={() => removeQuestion(q.key)}
              disabled={questions.length <= 1}
              title={questions.length <= 1 ? 'Нельзя удалить последний вопрос' : 'Удалить вопрос'}
            >
              Удалить вопрос
            </button>
          </div>

          <div className="polls-field">
            <input
              type="text"
              value={q.text}
              placeholder="Текст вопроса"
              required
              aria-label={`Текст вопроса ${qi + 1}`}
              onChange={(e) => updateQuestion(q.key, { text: e.target.value })}
            />
          </div>

          <div className="polls-field">
            <select
              value={q.type}
              aria-label={`Тип ответа на вопрос ${qi + 1}`}
              onChange={(e) => updateQuestion(q.key, { type: e.target.value })}
            >
              <option value="single">Один ответ</option>
              <option value="multiple">Несколько ответов</option>
            </select>
          </div>

          <div className="polls-answers-edit">
            {q.answers.map((a, ai) => (
              <div className="polls-answer-edit" key={a.key}>
                <input
                  type="text"
                  value={a.text}
                  placeholder={`Вариант ${ai + 1}`}
                  required
                  aria-label={`Вариант ${ai + 1}`}
                  onChange={(e) => updateAnswer(q.key, a.key, e.target.value)}
                />
                <button
                  type="button"
                  className="polls-btn-icon"
                  onClick={() => removeAnswer(q.key, a.key)}
                  disabled={q.answers.length <= 2}
                  title={q.answers.length <= 2 ? 'Нельзя удалить менее двух вариантов ответа' : 'Удалить вариант'}
                  aria-label={`Удалить вариант ${ai + 1}`}
                >
                  ✕
                </button>
              </div>
            ))}
            <button type="button" className="polls-btn polls-btn-secondary polls-btn-sm" onClick={() => addAnswer(q.key)}>
              + Добавить вариант
            </button>
          </div>
        </div>
      ))}

      <button
        type="button"
        className="polls-btn polls-btn-secondary"
        onClick={() => setQuestions((qs) => [...qs, newQuestion()])}
      >
        + Добавить вопрос
      </button>

      <div className="polls-form-footer">
        {onCancel ? (
          <button type="button" className="polls-btn polls-btn-secondary" onClick={onCancel}>Отмена</button>
        ) : <span />}
        <button type="submit" className="polls-btn polls-btn-primary" disabled={saving || endPassed}>
          {saving ? 'Сохранение…' : submitLabel}
        </button>
      </div>
    </form>
  );
}
