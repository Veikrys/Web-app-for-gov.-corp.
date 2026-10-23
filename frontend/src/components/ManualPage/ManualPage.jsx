import { useParams, useNavigate } from 'react-router-dom';
import { useTheme } from '../../context/ThemeContext';
import './ManualPage.css';

const pageTitles = {
  'standards': 'Стандарты обслуживания',
  'as-instruction': 'Инструкция пользователя АС',
  'pupe': 'Памятка по работе с обращениями "ПУПЭ"',
  'standard-request': 'Пример оформления стандартного запроса',
  'connection-loss': 'Памятка при потере связи отдела с УФК',
  'outlook': 'Работа в "Outlook"',
  'roskazna-mail': 'Подключение почты roskazna.ru',
  'forum': 'Инструкция по работе с форумом',
  'vks': 'Проведение ВКС',
  'remote-helper': 'Памятка "Удаленный помощник"',
  'tls-certificate': 'Замена сертификата Континент TLS',
  'sobi-eb': 'Вход в ЭБ через "ПОИБ СОБИ"',
  'sobi-instructions': 'Инструкции "ПОИБ СОБИ"',
  'skiao': '"СКИАО": настройка листа согласования',
  'telephony': 'Действия при поломке телефонии в районе',
  'emergency': 'Памятки при аварийных ситуациях',
  'worker': 'Документы на нового сотрудника',
  // Отдел РСиБИ
  'secbi-uc-order': 'Порядок реализации функций УЦ',
  'secbi-keys-order': 'Порядок изготовления ключей ЭП для сотрудника УФК',
  'secbi-docs': 'Документы в отдел РСиБИ',
};

export default function ManualPage({ pageId, content }) {
  const navigate = useNavigate();
  const { isDark } = useTheme();
  const title = pageTitles[pageId] || 'Документ';

  return (
    <div className="manual-page">
      <div className="manual-header">
        <h2 className="manual-title">{title}</h2>
      </div>
      <div className="manual-content">
        {content}
      </div>
    </div>
  );
}
