import { useLocation, useNavigate } from 'react-router-dom';
import { useTheme } from '../../context/ThemeContext';
import './SeparatorBar.css';

const pageTitles = {
  '/': 'Главная',
  '/ofk': 'Отделения',
  '/ubp': 'Клиенты',
  '/docs': 'Документы',
  '/budget': 'Бюджет',
  '/search': 'Поиск',
  '/certif': 'Сертификаты',
  '/arms': 'Технические паспорта',
  '/calendar': 'Календарь',
  '/phonetraf': 'Отчёты',
  '/admin': 'Администратор',
  '/tarusap': 'Частные лица',
  '/tarusau': 'Организации',
  '/help': 'Справка',
  '/library': 'Библиотека',
  '/depkadr': 'Отдел ГГСик',
  '/depadm': 'АФО',
  '/depsec': 'РСИБ',
  '/konkurs': 'Конкурсы',
  '/cafk': 'ЦАФК',
  '/depaud': 'ВКиА',
  '/veterans': 'Ветераны',
  '/ois/standards': 'ОИС / Стандарты обслуживания',
  '/ois/as-instruction': 'ОИС / Инструкция пользователя АС',
  '/ois/pupe': 'ОИС / Памятка по работе с обращениями "ПУПЭ"',
  '/ois/standard-request': 'ОИС / Пример оформления стандартного запроса',
  '/ois/connection-loss': 'ОИС / Памятка при потере связи отдела с УФК',
  '/ois/outlook': 'ОИС / Работа в "Outlook"',
  '/ois/roskazna-mail': 'ОИС / Подключение почты roskazna.ru',
  '/ois/forum': 'ОИС / Инструкция по работе с форумом',
  '/ois/vks': 'ОИС / Проведение ВКС',
  '/ois/remote-helper': 'ОИС / Памятка "Удаленный помощник"',
  '/ois/tls-certificate': 'ОИС / Замена сертификата Континент TLS',
  '/ois/sobi-eb': 'ОИС / Вход в ЭБ через "ПОИБ СОБИ"',
  '/ois/sobi-instructions': 'ОИС / Инструкции "ПОИБ СОБИ"',
  '/ois/skiao': 'ОИС / "СКИАО": настройка листа согласования',
  '/ois/telephony': 'ОИС / Действия при поломке телефонии в районе',
  '/ois/emergency': 'ОИС / Памятки при аварийных ситуациях',
};

export default function SeparatorBar() {
  const location = useLocation();
  const navigate = useNavigate();
  const { isDark, toggleTheme } = useTheme();

  const currentPath = location.pathname || '/';
  const currentTitle = pageTitles[currentPath] || 'Главная';

  const isHomePage = currentPath === '/';

  return (
    <div className="separator-bar" role="navigation" aria-label="Навигационная панель">
      <div className="separator-left">
        <button
          className={`separator-back ${isHomePage ? 'disabled' : ''}`}
          onClick={() => !isHomePage && navigate(-1)}
          disabled={isHomePage}
          aria-label={isHomePage ? 'На главной странице' : 'Вернуться назад'}
          title={isHomePage ? 'На главной странице' : 'Вернуться назад'}
        >
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path
              d="M12.5 4L6.5 10L12.5 16"
              stroke="currentColor"
              strokeWidth="2.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
          <span>Назад</span>
        </button>
      </div>

      <div className="separator-center">
        <span className="separator-breadcrumb" aria-label="Текущий раздел">
          {currentTitle}
        </span>
      </div>

      <div className="separator-right">
        <button
          className="separator-theme-toggle"
          onClick={toggleTheme}
          aria-label={isDark ? 'Переключить на светлую тему' : 'Переключить на тёмную тему'}
          title={isDark ? 'Светлая тема' : 'Тёмная тема'}
        >
          {isDark ? (
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="12" cy="12" r="5" stroke="currentColor" strokeWidth="2" />
              <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
            </svg>
          ) : (
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          )}
        </button>
      </div>
    </div>
  );
}
