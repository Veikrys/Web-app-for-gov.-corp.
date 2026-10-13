import MenuCategory from './MenuCategory';
import { menuData } from '../../data/menuData';
import './LeftMenu.css';

export default function LeftMenu() {
  return (
    <nav className="left-menu" role="navigation" aria-label="Главное меню">
      {menuData.map(category => (
        <MenuCategory key={category.id} {...category} />
      ))}
    </nav>
  );
}
