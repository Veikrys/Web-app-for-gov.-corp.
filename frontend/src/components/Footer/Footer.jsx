import { useEffect, useState } from 'react';
import './Footer.css';

export default function Footer() {
  const [activeUsers, setActiveUsers] = useState(0);

  useEffect(() => {
    const fetchUserCount = async () => {
      try {
        const response = await fetch('/api/active-users');
        if (!response.ok) return;
        const data = await response.json();
        setActiveUsers(Math.max(1, data.count));
      } catch {
        // silently fail
      }
    };
    fetchUserCount();
    const interval = setInterval(fetchUserCount, 30000);
    return () => clearInterval(interval);
  }, []);

  return (
    <footer className="footer" role="contentinfo">
      <div className="footer-content">
        <div className="footer-section">
          <h3 className="footer-heading">Средства разработки</h3>
          <ul className="footer-list">
            <li>React 19 + Vite 8</li>
            <li>Node.js + Express</li>
            <li>MySQL 8</li>
            <li>Docker + Nginx</li>
          </ul>
        </div>

        <div className="footer-section">
          <h3 className="footer-heading">Программное обеспечение</h3>
          <ul className="footer-list">
            <li>Google Chrome / Яндекс.Браузер</li>
            <li>XHTML 1.0 Transitional</li>
            <li>CSS 2.0</li>
          </ul>
        </div>

        <div className="footer-section">
          <h3 className="footer-heading">Разработка и оформление</h3>
          <p className="footer-author">
            Алексей Черников
          </p>
          <p className="footer-version">
            Версия 1.1.2
          </p>
        </div>

        <div className="footer-section">
          <h3 className="footer-heading">Статистика</h3>
          <div className="footer-users">
            <span className="users-icon" aria-hidden="true">👥</span>
            <span className="users-count">{activeUsers}</span>
            <span className="users-label">пользователей онлайн</span>
          </div>
        </div>
      </div>

      <div className="footer-bottom">
        <p>УФК по Калужской области, с 2026 г.</p>
      </div>
    </footer>
  );
}
