import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './MenuItem.css';

export default function MenuItem({ label, url, children, requiresAuth, spa, depth = 0 }) {
  const navigate = useNavigate();
  const [showChildren, setShowChildren] = useState(false);
  const hasChildren = children && children.length > 0;

  const handleClick = (e) => {
    if (requiresAuth) {
      e.preventDefault();
      alert(
        'Не удалось определить вашу учётную запись.\n\n' +
        'Возможно, вы используете Firefox.\n\n' +
        'Рекомендуется использовать Google Chrome или Яндекс.Браузер.'
      );
      return;
    }
    if (spa && !hasChildren) {
      // Внутренний переход без перезагрузки — меню остаётся раскрытым
      e.preventDefault();
      navigate(url);
      return;
    }
    if (hasChildren) {
      e.preventDefault();
      setShowChildren(prev => !prev);
    } else if (url === '#') {
      e.preventDefault();
    }
  };

  return (
    <li className="menu-item" role="none">
      <a
        href={hasChildren ? '#' : url}
        className={`menu-item-link ${hasChildren ? 'has-children' : ''}`}
        onClick={handleClick}
        role={hasChildren ? 'button' : 'menuitem'}
        aria-expanded={hasChildren ? showChildren : undefined}
        target={url.startsWith('http') ? '_blank' : undefined}
        rel={url.startsWith('http') ? 'noopener noreferrer' : undefined}
      >
        <span className="menu-item-bullet" aria-hidden="true">●</span>
        <span className="menu-item-label">{label}</span>
        {hasChildren && (
          <span className="menu-item-arrow" aria-hidden="true">
            {showChildren ? '▼' : '▶'}
          </span>
        )}
      </a>
      {showChildren && hasChildren && (
        <ul className="menu-sub-list" role="menu">
          {children.map((child, index) => (
            <MenuItem key={`sub-${depth}-${index}`} {...child} depth={depth + 1} />
          ))}
        </ul>
      )}
    </li>
  );
}
