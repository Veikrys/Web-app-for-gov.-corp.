import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';
import './VeteransLandingPage.css';

// Статический список плакатов (как в оригинальном veterans.php)
const POSTERS = [
  { file: 'stend_01.JPG', width: 800, height: 600 },
  { file: 'stend_02.JPG', width: 800, height: 470 },
  { file: 'stend_03.JPG', width: 800, height: 600 },
  { file: 'stend_04.JPG', width: 800, height: 467 },
  { file: 'stend_05.JPG', width: 800, height: 600 },
  { file: 'stend_06.JPG', width: 800, height: 600 },
  { file: 'stend_07.JPG', width: 800, height: 600 },
  { file: 'stend_08.JPG', width: 800, height: 600 },
  { file: 'stend_09.JPG', width: 800, height: 535 },
  { file: 'stend_11.JPG', width: 800, height: 600 },
  { file: 'stend_12.JPG', width: 800, height: 600 },
  { file: 'stend_13.JPG', width: 800, height: 600 },
  { file: 'stend_14.JPG', width: 800, height: 600 },
  { file: 'stend_15.JPG', width: 800, height: 600 },
  { file: 'stend_16.JPG', width: 800, height: 600 },
  { file: 'stend_17.JPG', width: 800, height: 600 },
  { file: 'stend_18.JPG', width: 800, height: 600 },
];

export default function PostersPage() {
  const navigate = useNavigate();
  const [zoomImage, setZoomImage] = useState(null);

  const handleBack = () => {
    navigate('/gallery/veterans');
  };

  const handlePhotoClick = (photoSrc) => {
    setZoomImage({ src: photoSrc, alt: '' });
  };

  return (
    <div className="veterans-page">
      <div className="veterans-header">
        <h2 className="veterans-title">
          <span className="veterans-title-icon">🎨</span>
          Плакаты
        </h2>
        <div className="veterans-photo-count">{POSTERS.length} плакатов</div>
      </div>

      <div className="veterans-posters-grid">
        {POSTERS.map((poster, idx) => (
          <div
            key={idx}
            className="veteran-poster-card"
            onClick={() => handlePhotoClick(`/img/photo70/${poster.file}`)}
            role="button"
            tabIndex={0}
            aria-label={`Посмотреть плакат ${idx + 1}`}
            onKeyDown={(e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handlePhotoClick(`/img/photo70/${poster.file}`);
              }
            }}
          >
            <img
              src={`/img/photo70/${poster.file}`}
              alt={`Плакат ${idx + 1}`}
              className="veteran-poster-img"
              loading="lazy"
            />
          </div>
        ))}
      </div>

      <ImageZoomModal
        image={zoomImage}
        onClose={() => setZoomImage(null)}
        zoom={1}
      />
    </div>
  );
}
