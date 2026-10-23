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
  '/depkadr': 'Отдел ГГСиК',
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
  '/worker': 'Документы на нового сотрудника',
  
  // ОГГСиК — основные страницы
  '/ggsik/declarations': 'ОГГСиК / Заявления',
  '/ggsik/legal-acts': 'ОГГСиК / Нормативно-правовые акты',
  '/ggsik/methods': 'ОГГСиК / Методические материалы',
  '/ggsik/forms': 'ОГГСиК / Формы и бланки',
  '/ggsik/commission': 'ОГГСиК / Деятельность комиссии',
  
  // ОГГСиК — подстраницы (Нормативно-правовые акты)
  '/ggsik/legal-acts/fz-230': 'ОГГСиК / НПА / ФЗ-230',
  '/ggsik/legal-acts/ukaz-885': 'ОГГСиК / НПА / Указ-885',
  '/ggsik/legal-acts/ukaz-815': 'ОГГСиК / НПА / Указ-815',
  '/ggsik/legal-acts/ukaz-559': 'ОГГСиК / НПА / Указ-559',
  '/ggsik/legal-acts/ukaz-561': 'ОГГСиК / НПА / Указ-561',
  '/ggsik/legal-acts/ukaz-613': 'ОГГСиК / НПА / Указ-613',
  '/ggsik/legal-acts/ukaz-1065': 'ОГГСиК / НПА / Указ-1065',
  '/ggsik/legal-acts/ukaz-460': 'ОГГСиК / НПА / Указ-460',
  '/ggsik/legal-acts/ukaz-297': 'ОГГСиК / НПА / Указ-297',
  '/ggsik/legal-acts/ukaz-821': 'ОГГСиК / НПА / Указ-821',
  '/ggsik/legal-acts/postanovlenie-700': 'ОГГСиК / НПА / Постановление-700',
  '/ggsik/legal-acts/prikaz-1n': 'ОГГСиК / НПА / Приказ-1н',
  '/ggsik/legal-acts/prikaz-11n': 'ОГГСиК / НПА / Приказ-11н',
  '/ggsik/legal-acts/prikaz-9n': 'ОГГСиК / НПА / Приказ-9н',
  '/ggsik/legal-acts/prikaz-7n': 'ОГГСиК / НПА / Приказ-7н',
  '/ggsik/legal-acts/prikaz-18n': 'ОГГСиК / НПА / Приказ-18н',
  '/ggsik/legal-acts/prikaz-6n': 'ОГГСиК / НПА / Приказ-6н',
  '/ggsik/legal-acts/prikaz-263': 'ОГГСиК / НПА / Приказ-263',
  '/ggsik/legal-acts/prikaz-283': 'ОГГСиК / НПА / Приказ-283',
  '/ggsik/legal-acts/prikaz-472': 'ОГГСиК / НПА / Приказ-472',
  
  // ОГГСиК — подстраницы (Методические материалы)
  '/ggsik/methods/razb-ml': 'ОГГСиК / Методические / Разъяснения МТ',
  '/ggsik/methods/protokol-4-17-74': 'ОГГСиК / Методические / Протокол 4/17/74',
  '/ggsik/methods/pismo-6993-17': 'ОГГСиК / Методические / Письмо 6993-17',
  '/ggsik/methods/pismo-17-3-726': 'ОГГСиК / Методические / Письмо 17-3/726',
  '/ggsik/methods/pamyatka-uvolnenie': 'ОГГСиК / Методические / Памятка при увольнении',
  '/ggsik/methods/ocenka-riskov': 'ОГГСиК / Методические / Оценка рисков',
  '/ggsik/methods/informaciya-ml-2013': 'ОГГСиК / Методические / Информация МТ 2013',
  '/ggsik/methods/obzor-rekomendacij': 'ОГГСиК / Методические / Обзор рекомендаций',
  
  // ОГГСиК — подстраницы (Деятельность комиссии)
  '/ggsik/commission/prikaz-2n': 'ОГГСиК / Комиссия / Приказ-2н',
  '/ggsik/commission/sostav-komissii': 'ОГГСиК / Комиссия / Состав комиссии',
  '/ggsik/commission/obrashenie': 'ОГГСиК / Комиссия / Обращение в комиссию',
  '/ggsik/commission/prikaz-9n-sluzebnaya-proverka': 'ОГГСиК / Комиссия / Приказ-9н (служебная проверка)',
  '/ggsik/commission/prikaz-360': 'ОГГСиК / Комиссия / Приказ-360',
  '/ggsik/commission/otchet-2012': 'ОГГСиК / Комиссия / Отчет за 2012 год',
  
  // РСиБИ
  '/secbi/uc-order': 'ОРСиБИ / Порядок реализации функций УЦ',
  '/secbi/keys-order': 'ОРСиБИ / Порядок изготовления ключей ЭП для сотрудника УФК',
  '/secbi/docs': 'ОРСиБИ / Документы в отдел РСиБИ',
  
  // Административно-финансовый отдел
  '/afo/landoks-medo': 'АФО / Инструкция по Ландокс МЭДО',
  '/afo/templates': 'АФО / Шаблоны документов',
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
              <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
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
