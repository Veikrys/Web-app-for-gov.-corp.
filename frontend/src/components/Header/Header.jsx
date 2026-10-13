import { useEffect, useState } from 'react';
import './Header.css';

const monthNames = [
  'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
  'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
];

const dayNames = [
  'воскресенье', 'понедельник', 'вторник', 'среда',
  'четверг', 'пятница', 'суббота',
];

export default function Header() {
  const [now, setNow] = useState(new Date());

  useEffect(() => {
    const interval = setInterval(() => setNow(new Date()), 1000);
    return () => clearInterval(interval);
  }, []);

  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const s = String(now.getSeconds()).padStart(2, '0');
  const timeStr = `${h}:${m}:${s}`;

  const day = now.getDate();
  const month = monthNames[now.getMonth()];
  const year = now.getFullYear();
  const dayName = dayNames[now.getDay()];

  return (
    <header className="header" role="banner">
      <div className="header-left">
        <img
          src="/img/logo.jpg"
          alt="Герб Федерального казначейства"
          className="header-glyph"
          width="56"
          height="56"
          onClick={() => (window.location.href = '/')}
          role="link"
          aria-label="Перейти на главную страницу"
          tabIndex={0}
          onKeyDown={(e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              window.location.href = '/';
            }
          }}
        />
        <h1 className="header-title">
          Управление Федерального Казначейства по Калужской области
        </h1>
      </div>
      <div className="header-right" aria-live="polite" aria-label="Текущие дата и время">
        <span className="header-date">
          {day}&nbsp;<i>{month}</i>&nbsp;{year}&nbsp;г.,&nbsp;<i>{dayName}</i>.
        </span>
        <span className="header-clock">{timeStr}</span>
      </div>
    </header>
  );
}
