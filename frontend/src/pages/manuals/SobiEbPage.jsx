import { useState } from 'react';
import ManualPage from '../../components/ManualPage/ManualPage';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';

const images = [
  { src: '/img/OIS/EB/tls_001.png', alt: 'Новая ссылка' },
  { src: '/img/OIS/EB/tls_002.png', alt: 'Скачивание настроек' },
  { src: '/img/OIS/EB/tls_003.png', alt: 'Импорт конфигурации' },
];

const ManualContent = ({ onImageClick }) => (
  <div className="manual-content">
    <h3>Вход в Электронный Бюджет через «ПОИБ СОБИ»</h3>
    <p>С 29.11.2021 <b>вход в ЭБ ПУиО</b> будет осуществляться по <a href="http://eb.cert.roskazna.ru/">🔗 новой ссылке</a>. Ссылка добавлена на стартовой странице. <b>Вход в другие подсистемы осуществляются по <a href="http://lk.budget.gov.ru/">🔗 старой ссылке</a></b>. В планах ФК перевести остальные системы ЭБ на новую ссылку, но сроков не называется.</p>
    <img src={images[0].src} alt={images[0].alt} className="manual-img" onClick={() => onImageClick(images[0])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Пользователям необходимо загрузить новые настройки для Континента TLS по следующей инструкции:</p>
    <p>Скачать новые настройки для Континента TLS, щелкнув по ссылке далее правой кнопкой мыши и выбрав «Сохранить объект как…» выбрать рабочий стол: <a href="/data/OIS/Настройки 27.11.2021 2.0.1440.0.json" download>⚙️ файл для скачивания (.JSON)</a>.</p>
    <img src={images[1].src} alt={images[1].alt} className="manual-img" onClick={() => onImageClick(images[1])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Открыть континент TLS, щелкнув по нему в правом нижнем углу или на ярлыке на рабочем столе.</p>
    <p>Нажать «Настройки» (на рисунке ниже 1), выбрать «Управление конфигурацией» (на рисунке ниже 2), нажать «Импортировать конфигурацию» (на рисунке ниже 3), выбрать файл «Настройки 27.11.2021 2.0.1440.0.json» на рабочем столе (на рисунке ниже 4) и нажать кнопку «Открыть» (на рисунке ниже 5). Появится окно Импорт выполнен успешно.</p>
    <img src={images[2].src} alt={images[2].alt} className="manual-img" onClick={() => onImageClick(images[2])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>В случае ошибок при загрузке настроек необходимо сделать обращение, с приложенной ошибкой и описанием: В ходе импорта новых настроек для Континента TLS произошла ошибка. Имя рабочей станции (например w3700ois002).</p>
  </div>
);

export default function SobiEbPage() {
  const [zoomImage, setZoomImage] = useState(null);
  return (
    <>
      <ManualPage pageId="sobi-eb" content={<ManualContent onImageClick={(img) => setZoomImage({ src: img.src, alt: img.alt })} />} />
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
