import { useMemo } from 'react';
import MenuCategory from './MenuCategory';
import { menuData } from '../../data/menuData';
import { usePollsList } from '../../pages/polls/pollsApi';
import './LeftMenu.css';

export default function LeftMenu() {
  const polls = usePollsList();

  // В пункт «Опросы» после «Менеджера опросов» подставляем созданные опросы
  const categories = useMemo(() => {
    const pollItems = polls.map((poll) => ({
      label: poll.title_short || poll.title,
      url: `/polls/${poll.id}`,
      spa: true,
    }));
    return menuData.map((category) => ({
      ...category,
      items: category.items.map((item) => (
        item.dynamic === 'polls' ? { ...item, children: [...item.children, ...pollItems] } : item
      )),
    }));
  }, [polls]);

  return (
    <nav className="left-menu" role="navigation" aria-label="Главное меню">
      {categories.map(category => (
        <MenuCategory key={category.id} {...category} />
      ))}
    </nav>
  );
}
