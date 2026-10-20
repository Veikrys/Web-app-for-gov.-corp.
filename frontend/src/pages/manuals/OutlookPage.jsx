import { useState } from 'react';
import ManualPage from '../../components/ManualPage/ManualPage';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';

const images = [
  { src: '/img/OIS/Outlook/outlook2010_ao1.png', alt: 'Файл' },
  { src: '/img/OIS/Outlook/outlook2010_ao2.png', alt: 'Сведения' },
  { src: '/img/OIS/Outlook/outlook2010_ao3.png', alt: 'Автоответы' },
  { src: '/img/OIS/Outlook/outlook2010_ao4.png', alt: 'Настройка периода' },
  { src: '/img/OIS/Outlook/outlook2010_ao5.png', alt: 'В пределах организации' },
  { src: '/img/OIS/Outlook/outlook2010_ao6.png', alt: 'Вторая вкладка' },
  { src: '/img/OIS/Outlook/outlook2010_ao7.png', alt: 'Автоответ за пределами организации' },
  { src: '/img/OIS/Outlook/outlook2010_ao8.png', alt: 'Правила' },
  { src: '/img/OIS/Outlook/outlook2010_ao9.png', alt: 'Отображать для всех конфигураций' },
  { src: '/img/OIS/Outlook/outlook2010_ao10.png', alt: 'Существующие правила' },
  { src: '/img/OIS/Outlook/outlook2010_ao11.png', alt: 'Удаление правила' },
  { src: '/img/OIS/Outlook/outlook2010_ao12.png', alt: 'Кнопка Добавить' },
  { src: '/img/OIS/Outlook/outlook2010_ao13.png', alt: 'Диалоговое окно' },
  { src: '/img/OIS/Outlook/outlook2010_ao14.png', alt: 'Переслать' },
  { src: '/img/OIS/Outlook/outlook2010_ao15.png', alt: 'Выбор адресата' },
  { src: '/img/OIS/Outlook/outlook2010_ao16.png', alt: 'ОК' },
  { src: '/img/OIS/Outlook/outlook2010_ao17.png', alt: 'Да' },
  { src: '/img/OIS/Outlook/outlook2010_ao18.png', alt: 'ОК' },
  { src: '/img/OIS/Outlook/outlook2010_ao19.png', alt: 'ОК' },
  { src: '/img/OIS/Outlook/outlook_ao1.png', alt: 'Автономный режим' },
  { src: '/img/OIS/Outlook/outlook_ao2.png', alt: 'Отключение автономного режима' },
];

const ManualContent = ({ onImageClick }) => (
  <div className="manual-content">
    <h3>Работа с клиентом электронной почты Microsoft Outlook</h3>
    
    <h4>Настройка пересылки сообщений пользователя на время его отпуска другому пользователю в Microsoft Outlook 2010</h4>
    
    <p>Щелкнуть левой кнопкой мыши на «Файл»:</p>
    <img src={images[0].src} alt={images[0].alt} className="manual-img" onClick={() => onImageClick(images[0])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>Щелкнуть левой кнопкой мыши на «Сведения»:</p>
    <img src={images[1].src} alt={images[1].alt} className="manual-img" onClick={() => onImageClick(images[1])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>Щелкнуть левой кнопкой мыши на «Автоответы»:</p>
    <img src={images[2].src} alt={images[2].alt} className="manual-img" onClick={() => onImageClick(images[2])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В следующем диалоговом окне выбрать и установить следующие параметры:</p>
    <ul>
      <li>выбрать «Отправлять автоответы»;</li>
      <li>поставить галочку «Отправлять только в течение следующего периода»;</li>
      <li>Выбрать время начала и время окончания отпуска (дата и время выбираются в разных полях из ниспадающих окон при нажатии следующих значков):</li>
    </ul>
    <img src={images[3].src} alt={images[3].alt} className="manual-img" onClick={() => onImageClick(images[3])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>В моем примере время начала: 6:00 05.02.2016, а время окончания: 21:00 04.03.2016.</p>
    
    <p>На вкладке ниже «В пределах организации» задать текст-оповещение о нахождении пользователя в отпуске:</p>
    <img src={images[4].src} alt={images[4].alt} className="manual-img" onClick={() => onImageClick(images[4])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>В моём примере я написал даты, которые указал выше, и пользователя, которому будут отправляться сообщения – Тарасевич И.С.</p>
    
    <p>Щёлкнуть левой кнопкой мыши на вторую вкладку:</p>
    <img src={images[5].src} alt={images[5].alt} className="manual-img" onClick={() => onImageClick(images[5])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В следующей форме выбрать и указать следующие параметры:</p>
    <ul>
      <li>Поставить галочку «Автоответ отправителям за пределами организации»;</li>
    </ul>
    <img src={images[6].src} alt={images[6].alt} className="manual-img" onClick={() => onImageClick(images[6])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <ul>
      <li>Выбрать «Любым отправителям за пределами организации»;</li>
      <li>Написать точно такое же текстовое сообщение, которое было написано на вкладке «В пределах организации».</li>
    </ul>
    
    <p>В нижней левой части окна «Автоответы…» найти кнопку «Правила…» и щелкнуть по ней левой кнопкой мыши:</p>
    <img src={images[7].src} alt={images[7].alt} className="manual-img" onClick={() => onImageClick(images[7])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В открывшемся окне поставить галочку «Отображать для всех конфигураций»:</p>
    <img src={images[8].src} alt={images[8].alt} className="manual-img" onClick={() => onImageClick(images[8])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p><span style={{ fontWeight: 'bold', color: 'red' }}>Только</span> если в окне уже есть существующие правила, необходимо:</p>
    <img src={images[9].src} alt={images[9].alt} className="manual-img" onClick={() => onImageClick(images[9])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <ul>
      <li>Выбрать одно правило;</li>
      <li>Нажать кнопку «Удалить» для удаления выбранного правила;</li>
      <li>В открывшемся диалоговом окне нажать кнопку «Да»:</li>
    </ul>
    <img src={images[10].src} alt={images[10].alt} className="manual-img" onClick={() => onImageClick(images[10])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Таким же способом (а-в) удалить все существующие правила.</p>
    
    <p>После того, как окно формы станет пустым (правил не будет), нажать кнопку «Добавить»:</p>
    <img src={images[11].src} alt={images[11].alt} className="manual-img" onClick={() => onImageClick(images[11])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p><span style={{ fontWeight: 'bold', color: 'red' }}>Внимание!</span> В открывшемся диалоговом окне поля «От…», «Кому…», «Тема», «Текст:» должны быть пустыми; также <span style={{ fontWeight: 'bold', color: 'red' }}>не надо</span> ставить галочки «Отправленные мне лично», «Копия мне».</p>
    <img src={images[12].src} alt={images[12].alt} className="manual-img" onClick={() => onImageClick(images[12])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В нижней части диалогового окна необходимо:</p>
    <ul>
      <li>поставить галочку «Переслать»;</li>
      <li>Нажать левой кнопкой мыши на «Кому…».</li>
    </ul>
    <img src={images[13].src} alt={images[13].alt} className="manual-img" onClick={() => onImageClick(images[13])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В новом окне выбрать адресата, которому будут пересылаться сообщения на время отпуска пользователя, для этого:</p>
    <ul>
      <li>Написать в строку поиска полные ФИО адресата;</li>
      <li>Двойным щелчком мыши добавить найденный контакт адресата в поле «Кому -{'>'}»;</li>
      <li>Нажать кнопку «ОК».</li>
    </ul>
    <img src={images[14].src} alt={images[14].alt} className="manual-img" onClick={() => onImageClick(images[14])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В следующем диалоговом окне нажать кнопку «ОК»:</p>
    <img src={images[15].src} alt={images[15].alt} className="manual-img" onClick={() => onImageClick(images[15])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В открывшемся диалоговом окне нажать кнопку «Да»:</p>
    <img src={images[16].src} alt={images[16].alt} className="manual-img" onClick={() => onImageClick(images[16])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В следующем диалоговом окне нажать кнопку «ОК»:</p>
    <img src={images[17].src} alt={images[17].alt} className="manual-img" onClick={() => onImageClick(images[17])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>В следующем диалоговом окне также нажать кнопку «ОК»:</p>
    <img src={images[18].src} alt={images[18].alt} className="manual-img" onClick={() => onImageClick(images[18])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>Пересылка сообщений пользователя на время его отпуска другому пользователю в Microsoft Outlook 2010 настроена; установленное правило пересылки сообщений будет действовать только и только в период времени, установленный в пункте данной инструкции. До начала этого периода, а также после завершения этого периода времени, правило пересылки действовать не будет. Поэтому, если Вы хотите проверить уже сейчас, работает ли пересылка, необходимо изменить период времени, установленный в пункте данной инструкции таким образом, чтобы он включал текущее время и текущую дату.</p>
    
    <h4>Работа Outlook в автономном режиме</h4>
    <p>В Outlook есть возможность работать в автономном режиме, т.е. работать без подключения к почтовому серверу. Для работы сотрудникам Управления Федерального казначейства по Калужской области этот функционал не нужен. Периодически случается так что сотрудник по незнанию или не внимательности включает режим автономной работы.</p>
    
    <p>При включенном автономном режиме:</p>
    <ul>
      <li>не приходит почта от других сотрудников;</li>
      <li>нельзя отправить почту другим сотрудникам;</li>
      <li>справочник сотрудников в outlook пуст.</li>
    </ul>
    
    <img src={images[19].src} alt={images[19].alt} className="manual-img" onClick={() => onImageClick(images[19])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>Как определить, что у вас включен автономный режим.</p>
    <p>Если в правом нижнем углу outlook и в области уведомления есть красный крестик, то это значит, что у вас включен автономный режим.</p>
    
    <p>Для отключения автономного режима необходимо в outlook выбрать позицию меню <b>Файл</b> и снять галку <b>Автономный режим</b>.</p>
    
    <img src={images[20].src} alt={images[20].alt} className="manual-img" onClick={() => onImageClick(images[20])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    
    <p>Если Outlook, при попытке отключении автономного режима запрашивает имя пользователя и пароль для подключения к серверу, это может указывать на то, что учетная запись пользователя заблокирована. В такой ситуации попытайтесь ввести свое имя и пароль (как при входе в компьютер), если же это не поможет, обратитесь к администратору локальной сети Управления.</p>
  </div>
);

export default function OutlookPage() {
  const [zoomImage, setZoomImage] = useState(null);
  return (
    <>
      <ManualPage pageId="outlook" content={<ManualContent onImageClick={(img) => setZoomImage({ src: img.src, alt: img.alt })} />} />
      {zoomImage && (
        <ImageZoomModal
          image={zoomImage}
          onClose={() => setZoomImage(null)}
          zoom={1.5}
        />
      )}
    </>
  );
}
