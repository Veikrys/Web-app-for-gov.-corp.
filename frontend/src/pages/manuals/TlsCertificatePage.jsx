import { useState } from 'react';
import ManualPage from '../../components/ManualPage/ManualPage';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';

const images = [
  { src: '/img/OIS/TLS/ktls-1_01.png', alt: 'Удаление сертификата' },
  { src: '/img/OIS/TLS/ktls-1_02.png', alt: 'Импорт сертификата' },
  { src: '/img/OIS/TLS/ktls-1_03.png', alt: 'Импорт конфигурации' },
];

const ManualContent = ({ onImageClick }) => (
  <div className="manual-content">
    <h3>Порядок замены сертификата сервера в «Континент TLS Клиент»</h3>
    
    <h4>Копирование нового сертификата сервера TLS на АРМ пользователя</h4>
    <p>Скопировать сертификат (X:\Личные папки\Общая\Сертификаты\Для ЭБ\lk.budget.gov.ru.crt) к себе на компьютер на рабочий стол.</p>
    <p>Скачать <a href="/data/OIS/Настройки 07.07.2020.json" download>⚙️ файл с настройками (.JSON)</a> к себе на компьютер на рабочий стол.</p>
    
    <h4>Замена сертификата сервера TLS в СКЗИ «Континент TLS Клиент»</h4>
    <p>Для замены сертификата сервера TLS в ПО «Континент TLS Клиент» версии 2.0 на АРМ пользователя необходимо:</p>
    <ol>
      <li>Выполнить запуск «Континент TLS Клиент». Для этого в меню «Пуск» все программы (программы) найти «Код безопасности- Континент TLS клиент» и щелкнуть по найденному элементу.</li>
      <li>В разделе «Управление сертификатами» перейти на вкладку «Серверные сертификаты». Откроется перечень установленных серверных сертификатов.</li>
      <li>Выполнить удаление сертификата сервера «Федеральное казначейство». Для этого выделить сертификат и нажать кнопку «Удалить». В окне с запросом подтверждения удаления сертификата сервера нажать «ДА»:</li>
    </ol>
    <img src={images[0].src} alt={images[0].alt} className="manual-img" onClick={() => onImageClick(images[0])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <ol start="4">
      <li>Импортировать новый сертификат сервера «Федеральное казначейство». Для этого нажать кнопку «Импортировать». В открывшемся окне перейти в директорию, в которую был скопирован сертификат на первом шаге данной инструкции, определить формат файлов как «Все файлы» и выбрать сертификат:</li>
    </ol>
    <img src={images[1].src} alt={images[1].alt} className="manual-img" onClick={() => onImageClick(images[1])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <ol start="5">
      <li>Нажать «Настройки» (на рисунке ниже 1), выбрать «Управление конфигурацией» (на рисунке ниже 2), нажать «Импортировать конфигурацию» (на рисунке ниже 3), выбрать файл «Настройки 07.07.2020» на рабочий стол (на рисунке ниже 4) и нажать кнопку «Открыть» (на рисунке ниже 5). Появится окно Импорт выполнен успешно:</li>
    </ol>
    <img src={images[2].src} alt={images[2].alt} className="manual-img" onClick={() => onImageClick(images[2])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <ol start="6">
      <li>Перед выполнением подключения к личному кабинету ГИИС Электронный бюджет с новым сертификатом сервера требуется выполнить «Сброс соединений» в Континент TLS клиенте. Для этого требуется в правом нижнем углу рабочего стола на значке Континент TLS клиента нажать правой кнопкой мыши и выбрать пункт «Сброс соединений», затем в браузере зайти в ЭБ.</li>
      <li>После успешного входа удалить сертификат и файл с настройками с рабочего стола.</li>
    </ol>
  </div>
);

export default function TlsCertificatePage() {
  const [zoomImage, setZoomImage] = useState(null);
  return (
    <>
      <ManualPage pageId="tls-certificate" content={<ManualContent onImageClick={(img) => setZoomImage({ src: img.src, alt: img.alt })} />} />
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
