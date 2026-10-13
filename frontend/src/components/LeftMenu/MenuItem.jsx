import { useState } from 'react';
import './MenuItem.css';

export default function MenuItem({ label, url, children, requiresAuth, depth = 0 }) {
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
            <li key={`sub-${depth}-${index}`} role="none">
              <MenuItem {...child} depth={depth + 1} />
            </li>
          ))}
        </ul>
      )}
    </li>
  );
}
