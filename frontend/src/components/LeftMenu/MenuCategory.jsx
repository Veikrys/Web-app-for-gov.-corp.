import { useState } from 'react';
import MenuItem from './MenuItem';
import './MenuCategory.css';

export default function MenuCategory({ id, title, items }) {
  const [isOpen, setIsOpen] = useState(false);

  const visibleItems = items.filter(item => {
    if (item.adminOnly) return false;
    return true;
  });

  return (
    <div className="menu-category">
      <button
        className="menu-category-header"
        onClick={() => setIsOpen(prev => !prev)}
        aria-expanded={isOpen}
        aria-controls={`menu-${id}`}
      >
        <span className="menu-category-title">{title}</span>
        <span className="menu-category-arrow" aria-hidden="true">
          {isOpen ? '▼' : '▶'}
        </span>
      </button>
      <ul
        id={`menu-${id}`}
        className={`menu-category-list ${isOpen ? 'open' : ''}`}
        role="menu"
      >
        {visibleItems.map((item, index) => (
          <MenuItem key={`${id}-${index}`} {...item} />
        ))}
      </ul>
    </div>
  );
}
