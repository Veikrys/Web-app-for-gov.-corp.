import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { pollsRequest } from './pollsApi';
import './Polls.css';

export default function PollResultsPage() {
  const { id } = useParams();
  const [data, setData] = useState(null);
  const [error, setError] = useState(null);
  const [open, setOpen] = useState({}); // раскрытые списки голосовавших: "qi-ai" → true

  useEffect(() => {
    setData(null);
    setError(null);
    pollsRequest(`/${id}/results`).then(setData).catch((err) => setError(err.message));
  }, [id]);

  const header = (
    <div className="polls-header">
      <h2 className="polls-title">Результаты опроса</h2>
      <Link to={`/polls/${id}`} className="polls-back-btn">← Вернуться к опросу</Link>
    </div>
  );

  if (error) return <div className="polls-page">{header}<div className="polls-error">{error}</div></div>;
  if (!data) return <div className="polls-page">{header}<div className="polls-loading">Загрузка…</div></div>;

  const isPublic = data.voting_type === 'public';
  const toggle = (key) => setOpen((prev) => ({ ...prev, [key]: !prev[key] }));

  return (
    <div className="polls-page">
      {header}
      <p className="polls-results-subtitle">
        {data.title}
        <span className="polls-results-type">({isPublic ? 'публичный' : 'анонимный'})</span>
      </p>

      {data.questions.map((q, qi) => (
        <div className="polls-question-card" key={q.id}>
          <h3 className="polls-result-question">{qi + 1}. {q.text}</h3>
          {q.answers.map((a, ai) => {
            const key = `${qi}-${ai}`;
            const expandable = isPublic && a.count > 0;
            return (
              <div className="polls-result-row" key={ai}>
                <div className="polls-result-head">
                  <span className="polls-result-text" style={a.percent === 0 ? { opacity: 0.6 } : undefined}>{a.text}</span>
                  <span className="polls-result-stats">{a.count} ({a.percent}%)</span>
                </div>
                <div
                  className={`polls-bar-track ${expandable ? 'clickable' : ''}`}
                  role={expandable ? 'button' : undefined}
                  tabIndex={expandable ? 0 : -1}
                  aria-expanded={expandable ? !!open[key] : undefined}
                  onClick={expandable ? () => toggle(key) : undefined}
                  onKeyDown={expandable ? (e) => {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(key); }
                  } : undefined}
                >
                  <div className="polls-bar" style={{ width: `${a.percent}%` }} />
                </div>
                {expandable && open[key] && (
                  <ul className="polls-voters">
                    {a.voters.map((v, vi) => <li key={vi}>{v}</li>)}
                  </ul>
                )}
              </div>
            );
          })}
        </div>
      ))}

      <div className="polls-summary">
        <div>
          <strong>Проголосовало:</strong> {data.totalVoters} человек
          {isPublic && <small>Доступна детальная статистика — нажмите на полоски</small>}
        </div>
        <a href={`/api/polls/${id}/export.csv`} className="polls-btn polls-btn-primary" download>
          💾 Скачать CSV
        </a>
      </div>
    </div>
  );
}
