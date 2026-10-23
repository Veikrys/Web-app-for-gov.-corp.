import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import PollEditor from './PollEditor';
import { pollsRequest, notifyPollsChanged } from './pollsApi';
import './Polls.css';

export default function PollEditPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [poll, setPoll] = useState(null);
  const [loadError, setLoadError] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    setPoll(null);
    setLoadError(null);
    pollsRequest(`/${id}/edit`).then(setPoll).catch((err) => setLoadError(err.message));
  }, [id]);

  const handleSave = async (payload) => {
    setError(null);
    try {
      await pollsRequest(`/${id}`, { method: 'PUT', body: payload });
      notifyPollsChanged();
      navigate(`/polls/${id}`);
    } catch (err) {
      setError(err.message);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  const header = (
    <div className="polls-header">
      <h2 className="polls-title">Редактирование опроса</h2>
    </div>
  );

  if (loadError) return <div className="polls-page">{header}<div className="polls-error">{loadError}</div></div>;
  if (!poll) return <div className="polls-page">{header}<div className="polls-loading">Загрузка…</div></div>;

  return (
    <div className="polls-page">
      {header}
      {error && <div className="polls-error">{error}</div>}
      <PollEditor
        initial={poll}
        withStartDate
        submitLabel="Сохранить изменения"
        onSubmit={handleSave}
        onCancel={() => navigate(`/polls/${id}`)}
      />
    </div>
  );
}
