import { useState } from 'react';
import ManualPage from '../../components/ManualPage/ManualPage';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';

const images = [
  { src: '/img/OIS/Helper/remhelp_01.png', alt: 'Запрос на подключение' },
  { src: '/img/OIS/Helper/remhelp_02.png', alt: 'Доступ разрешён' },
  { src: '/img/OIS/Helper/remhelp_03.png', alt: 'Разрешение на управление рабочим столом' },
  { src: '/img/OIS/Helper/remhelp_04.png', alt: 'Подключение завершено' },
];

const ManualContent = ({ onImageClick }) => (
  <div className="manual-content">
    <h3>Удаленный помощник</h3>
    <p>В связи с рекомендациями ФК письмо № 11-04-03\6744 от 24.03.2022, Управлениям необходимо отказаться от использования RAdmin (программа для удаленного подключения). В связи с новыми требованиями, Управление переходит на работу с «Удаленный помощник».</p>
    <p>Когда к Вам будут подключаться, вы увидите соответствующий запрос, где будет написано кто именно к вам хочет подключиться:</p>
    <img src={images[0].src} alt={images[0].alt} className="manual-img" onClick={() => onImageClick(images[0])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>После того как вы разрешите доступ, подключившийся сможет только видеть ваш рабочий стол, и Вы увидите следующее окно:</p>
    <img src={images[1].src} alt={images[1].alt} className="manual-img" onClick={() => onImageClick(images[1])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Для того чтобы начать помогать и что-то делать на Вашем компьютере, подключившийся запросит «Разрешение на управление рабочим столом», без этого разрешения подключившийся сможет только наблюдать за вашими действиями:</p>
    <img src={images[2].src} alt={images[2].alt} className="manual-img" onClick={() => onImageClick(images[2])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Когда помощь будет оказана, подключавшийся отключится, и вы увидите следующее окно:</p>
    <img src={images[3].src} alt={images[3].alt} className="manual-img" onClick={() => onImageClick(images[3])} loading="lazy" style={{ cursor: 'zoom-in' }} />
    <p>Теперь окно «Удаленный помощник» можно закрыть, чтобы оно вам не мешало. При следующем подключении, появится новое.</p>
  </div>
);

export default function RemoteHelperPage() {
  const [zoomImage, setZoomImage] = useState(null);
  return (
    <>
      <ManualPage pageId="remote-helper" content={<ManualContent onImageClick={(img) => setZoomImage({ src: img.src, alt: img.alt })} />} />
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
