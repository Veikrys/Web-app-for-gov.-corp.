import { useState, useEffect, useCallback } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { pollsRequest, notifyPollsChanged, formatPollDate } from './pollsApi';
import './Polls.css';

export default function PollPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [poll, setPoll] = useState(null);
  const [answers, setAnswers] = useState({});
  const [loadError, setLoadError] = useState(null);
  const [message, setMessage] = useState(null); // { type: 'error' | 'success', text }
  const [busy, setBusy] = useState(false);

  const load = useCallback(() => {
    setLoadError(null);
    pollsRequest(`/${id}`).then(setPoll).catch((err) => setLoadError(err.message));
  }, [id]);

  useEffect(() => {
    setPoll(null);
    setAnswers({});
    setMessage(null);
    load();
  }, [load]);

  if (loadError) {
    return (
      <div className="polls-page">
        <div className="polls-header"><h2 className="polls-title">Опрос</h2></div>
        <div className="polls-error">{loadError}</div>
      </div>
    );
  }
  if (!poll) {
    return (
      <div className="polls-page">
        <div className="polls-header"><h2 className="polls-title">Опрос</h2></div>
        <div className="polls-loading">Загрузка…</div>
      </div>
    );
  }

  const locked = !poll.active || poll.voted;
  const isChecked = (qi, ai, q) => {
    if (poll.voted) return poll.myAnswers.includes(`${q.id}_${ai}`);
    return q.type === 'single' ? answers[qi] === ai : (answers[qi] || []).includes(ai);
  };

  const choose = (qi, ai, q) => setAnswers((prev) => {
    if (q.type === 'single') return { ...prev, [qi]: ai };
    const cur = prev[qi] || [];
    return { ...prev, [qi]: cur.includes(ai) ? cur.filter((x) => x !== ai) : [...cur, ai] };
  });

  const submitVote = async () => {
    const allAnswered = poll.questions.every((q, qi) => (
      q.type === 'single' ? answers[qi] !== undefined : (answers[qi] || []).length > 0
    ));
    if (!allAnswered) {
      setMessage({ type: 'error', text: 'Пожалуйста, ответьте на все вопросы, выбрав хотя бы один вариант в каждом.' });
      return;
    }
    setBusy(true);
    try {
      await pollsRequest(`/${id}/vote`, { method: 'POST', body: { answers } });
      setMessage({ type: 'success', text: 'Спасибо за ваш голос!' });
      load();
    } catch (err) {
      setMessage({ type: 'error', text: err.message });
    } finally {
      setBusy(false);
    }
  };

  const unvote = async () => {
    if (!window.confirm('Вы уверены, что хотите отменить свой голос?')) return;
    try {
      await pollsRequest(`/${id}/vote`, { method: 'DELETE' });
      setAnswers({});
      setMessage({ type: 'success', text: 'Голос отменён' });
      load();
    } catch (err) {
      setMessage({ type: 'error', text: err.message });
    }
  };

  const changePoll = () => {
    if (window.confirm('Вы уверены, что хотите редактировать этот опрос?')) navigate(`/polls/${id}/edit`);
  };

  const deletePoll = async () => {
    if (!window.confirm('Вы уверены, что хотите УДАЛИТЬ этот опрос? Это действие нельзя отменить.')) return;
    try {
      await pollsRequest(`/${id}`, { method: 'DELETE' });
      notifyPollsChanged();
      navigate('/polls/manager');
    } catch (err) {
      setMessage({ type: 'error', text: err.message });
    }
  };

  const shareHref = `mailto:?subject=${encodeURIComponent(poll.title)}&body=${encodeURIComponent(
    `Пройдите опрос: ${window.location.origin}/polls/${poll.id}`,
  )}`;

  const action = (handler) => (e) => { e.preventDefault(); handler(); };

  return (
    <div className="polls-page">
      <div className="polls-card">
        <h2 className="polls-poll-title">{poll.title}</h2>

        {!poll.active && <div className="polls-error">Опрос завершён</div>}
        {poll.voted && poll.active && (
          <div className="polls-info">Вы уже проголосовали. Чтобы изменить ответ, отмените голос.</div>
        )}
        {message && <div className={message.type === 'error' ? 'polls-error' : 'polls-success'}>{message.text}</div>}

        <div className="polls-period">
          Опрос действует с {formatPollDate(poll.start_date)} по {formatPollDate(poll.end_date)}
        </div>

        {poll.questions.map((q, qi) => (
          <fieldset className="polls-question" key={q.id} disabled={locked}>
            <legend className="polls-question-text">{q.text}</legend>
            <div className="polls-answers">
              {q.answers.map((a, ai) => (
                <label className="polls-answer" key={ai}>
                  <input
                    type={q.type === 'single' ? 'radio' : 'checkbox'}
                    name={`answer-${qi}`}
                    checked={isChecked(qi, ai, q)}
                    onChange={() => choose(qi, ai, q)}
                  />
                  <span>{a}</span>
                </label>
              ))}
            </div>
          </fieldset>
        ))}

        <button type="button" className="polls-btn polls-btn-primary" onClick={submitVote} disabled={locked || busy}>
          Проголосовать
        </button>

        <div className="polls-actions">
          {poll.voted && poll.active && <a href="#unvote" onClick={action(unvote)}>Отменить голос</a>}
          {poll.permissions.results && <Link to={`/polls/${id}/results`}>Посмотреть результаты</Link>}
          {poll.permissions.results && <a href={shareHref}>Поделиться</a>}
          {poll.permissions.edit && <a href="#edit" onClick={action(changePoll)}>Редактировать опрос</a>}
          {poll.permissions.edit && (
            <a href="#delete" className="polls-danger-link" onClick={action(deletePoll)}>Удалить опрос</a>
          )}
        </div>
      </div>
    </div>
  );
}
