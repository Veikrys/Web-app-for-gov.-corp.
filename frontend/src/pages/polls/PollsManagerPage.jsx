import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import PollEditor from './PollEditor';
import { pollsRequest, notifyPollsChanged } from './pollsApi';
import './Polls.css';

export default function PollsManagerPage() {
  const navigate = useNavigate();
  const [error, setError] = useState(null);

  const handleCreate = async (payload) => {
    setError(null);
    try {
      const { id } = await pollsRequest('', { method: 'POST', body: payload });
      notifyPollsChanged();
      navigate(`/polls/${id}`);
    } catch (err) {
      setError(err.message);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };

  return (
    <div className="polls-page">
      <div className="polls-header">
        <h2 className="polls-title">Менеджер опросов</h2>
      </div>
      {error && <div className="polls-error">{error}</div>}
      <PollEditor submitLabel="Создать опрос" onSubmit={handleCreate} />
    </div>
  );
}
