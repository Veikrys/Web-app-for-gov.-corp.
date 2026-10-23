import { useState, useEffect, useCallback } from 'react';

const BASE = '/api/polls';
const CHANGED_EVENT = 'polls:changed';

export async function pollsRequest(path = '', options = {}) {
  const { body, ...rest } = options;
  const res = await fetch(BASE + path, {
    ...rest,
    headers: body ? { 'Content-Type': 'application/json' } : undefined,
    body: body ? JSON.stringify(body) : undefined,
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok || data.error) {
    throw new Error(data.error || 'Ошибка при обращении к серверу');
  }
  return data;
}

// Сообщает левому меню, что список опросов изменился (создан / изменён / удалён)
export function notifyPollsChanged() {
  window.dispatchEvent(new Event(CHANGED_EVENT));
}

// Список опросов для левого меню; обновляется при notifyPollsChanged()
export function usePollsList() {
  const [polls, setPolls] = useState([]);

  const load = useCallback(() => {
    pollsRequest()
      .then(setPolls)
      .catch((err) => console.error('Ошибка загрузки списка опросов:', err));
  }, []);

  useEffect(() => {
    load();
    window.addEventListener(CHANGED_EVENT, load);
    return () => window.removeEventListener(CHANGED_EVENT, load);
  }, [load]);

  return polls;
}

// "2026-09-26T15:55" → "2026.09.26 15:55" (как на старом сайте)
export const formatPollDate = (value) => String(value || '').replace('T', ' ').replace(/-/g, '.');
