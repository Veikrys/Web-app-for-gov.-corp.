import { useState } from 'react';
import ManualPage from '../../components/ManualPage/ManualPage';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';

const images = [
  { src: '/img/OIS/VKS/vks_01.png', alt: 'На визирование' },
];

const ManualContent = ({ onImageClick }) => (
  <div className="manual-content">
    <h3>Порядок проведения ВКС для территориальных отделов</h3>
    <p>Во время трансляции в «Комнате для совещаний» должен быть включен только ОДИН микрофон. Ваши микрофоны должны быть выключены.</p>
    <img src={images[0].src} alt={images[0].alt} className="manual-img" onClick={() => onImageClick(images[0])} loading="lazy" style={{ cursor: 'zoom-in' }} />
  </div>
);

export default function VksPage() {
  const [zoomImage, setZoomImage] = useState(null);
  return (
    <>
      <ManualPage pageId="vks" content={<ManualContent onImageClick={(img) => setZoomImage({ src: img.src, alt: img.alt })} />} />
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
