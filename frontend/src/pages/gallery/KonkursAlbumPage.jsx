import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';
import { useTheme } from '../../context/ThemeContext';
import './GalleryPage.css';

const SUB_ALBUMS = [
  { ofk: 3700, name: 'Конкурсные фотографии Управления ФК', icon: '🏛️' },
  { ofk: 3702, name: 'Отдел 3702', icon: '📁' },
  { ofk: 3707, name: 'Отдел 3707', icon: '📁' },
  { ofk: 3709, name: 'Отдел 3709', icon: '📁' },
  { ofk: 3714, name: 'Отдел 3714', icon: '📁' },
  { ofk: 3716, name: 'Отдел 3716', icon: '📁' },
  { ofk: 3717, name: 'Отдел 3717', icon: '📁' },
  { ofk: 3718, name: 'Отдел 3718', icon: '📁' },
  { ofk: 3719, name: 'Отдел 3719', icon: '📁' },
  { ofk: 3721, name: 'Отдел 3721', icon: '📁' },
  { ofk: 3722, name: 'Отдел 3722', icon: '📁' },
  { ofk: 3723, name: 'Отдел 3723', icon: '📁' },
  { ofk: 3726, name: 'Отдел 3726', icon: '📁' },
];

const MONTHS_RU = [
  '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
  'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
];

const TAG_LABELS = {
  0: null,
  1: 'Фотография',
  2: 'Фотоколлаж',
  51: '1-е место',
  52: '2-е место',
  53: '3-е место',
};

function formatDate(dateStr) {
  if (!dateStr) return '';
  const day = dateStr.slice(8, 10);
  const month = parseInt(dateStr.slice(5, 7), 10);
  const year = dateStr.slice(0, 4);
  return `${day} ${MONTHS_RU[month]} ${year} г.`;
}

export default function KonkursAlbumPage() {
  const { ofk } = useParams();
  const navigate = useNavigate();
  const { isDark } = useTheme();
  const [photos, setPhotos] = useState([]);
  const [album, setAlbum] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [zoomImage, setZoomImage] = useState(null);

  useEffect(() => {
    // If a specific ofk is provided, fetch photos
    if (ofk && !isNaN(parseInt(ofk, 10))) {
      const albumInfo = SUB_ALBUMS.find((a) => a.ofk === parseInt(ofk, 10));
      setAlbum(albumInfo || { name: `Отдел ${ofk}`, icon: '📷' });

      fetch(`/api/gallery/album/${ofk}`)
        .then((res) => res.json())
        .then((data) => {
          setPhotos(data);
          setLoading(false);
        })
        .catch((err) => {
          console.error('Ошибка загрузки фотографий:', err);
          setError('Не удалось загрузить фотографии');
          setLoading(false);
        });
    } else {
      // Show sub-albums list
      setAlbum({ name: 'Конкурсные фото УФК и ОФК', icon: '📸' });
      setLoading(false);
    }
  }, [ofk]);

  const handleBack = () => {
    if (ofk && !isNaN(parseInt(ofk, 10))) {
      navigate('/gallery/konkurs');
    } else {
      navigate('/gallery');
    }
  };

  const handleSubAlbumClick = (subOfk) => {
    navigate(`/gallery/konkurs/${subOfk}`);
  };

  const handlePhotoClick = (photo) => {
    setZoomImage({
      src: `/img/photo20/${photo.file_full}`,
      alt: photo.description || '',
    });
  };

  // Show sub-albums list
  if (!ofk || isNaN(parseInt(ofk, 10))) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <h2 className="gallery-title">
            <span className="gallery-album-icon">📸</span>
            Конкурсные фото УФК и ОФК
          </h2>
        </div>

        <div className="konkurs-sub-grid">
          {SUB_ALBUMS.map((sub) => (
            <div
              key={sub.ofk}
              className="konkurs-sub-tile"
              onClick={() => handleSubAlbumClick(sub.ofk)}
              role="button"
              tabIndex={0}
              aria-label={`Открыть ${sub.name}`}
              onKeyDown={(e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                  e.preventDefault();
                  handleSubAlbumClick(sub.ofk);
                }
              }}
            >
              <div className="konkurs-sub-tile-icon">{sub.icon}</div>
              <div className="konkurs-sub-tile-name">{sub.name}</div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  // Show photos for specific album
  if (loading) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <button className="gallery-back-btn" onClick={handleBack}>
            ← Назад
          </button>
          <h2 className="gallery-title">
            {album?.icon && <span className="gallery-album-icon">{album.icon}</span>}
            {album?.name || 'Загрузка…'}
          </h2>
        </div>
        <div className="gallery-loading">Загрузка фотографий…</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <button className="gallery-back-btn" onClick={handleBack}>
            ← Назад
          </button>
          <h2 className="gallery-title">
            {album?.icon && <span className="gallery-album-icon">{album.icon}</span>}
            {album?.name || 'Фотогалерея'}
          </h2>
        </div>
        <div className="gallery-error">{error}</div>
      </div>
    );
  }

  return (
    <div className="gallery-page">
      <div className="gallery-header">
        <button className="gallery-back-btn" onClick={handleBack}>
          ← Назад
        </button>
        <h2 className="gallery-title">
          {album?.icon && <span className="gallery-album-icon">{album.icon}</span>}
          {album?.name || 'Фотогалерея'}
        </h2>
        <div className="gallery-photo-count">{photos.length} фото</div>
      </div>

      {photos.length === 0 ? (
        <div className="gallery-empty">В этом альбоме пока нет фотографий</div>
      ) : (
        <div className="photo-grid">
          {photos.map((photo) => (
            <div
              key={photo.ID_P}
              className="photo-card"
              onClick={() => handlePhotoClick(photo)}
              role="button"
              tabIndex={0}
              aria-label={`Посмотреть фото: ${photo.description || 'Без описания'}`}
              onKeyDown={(e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                  e.preventDefault();
                  handlePhotoClick(photo);
                }
              }}
            >
              <div className="photo-card-img-wrap">
                <img
                  src={`/img/photo20/${photo.file_small}`}
                  alt={photo.description || ''}
                  className="photo-card-img"
                  loading="lazy"
                />
              </div>
              <div className="photo-card-info">
                {photo.description && (
                  <div className="photo-card-desc">{photo.description}</div>
                )}
                <div className="photo-card-meta">
                  <span className="photo-card-date">{formatDate(photo.date_photo)}</span>
                  {photo.tag && TAG_LABELS[photo.tag] && (
                    <span className="photo-card-tag">{TAG_LABELS[photo.tag]}</span>
                  )}
                  {photo.c_m > 0 && (
                    <span className="photo-card-rating">
                      ⭐ {Number(photo.s_m).toFixed(1)} / 5 ({photo.c_m})
                    </span>
                  )}
                </div>
                {photo.Comment && (
                  <div className="photo-card-comment">{photo.Comment}</div>
                )}
              </div>
            </div>
          ))}
        </div>
      )}

      <ImageZoomModal
        image={zoomImage}
        onClose={() => setZoomImage(null)}
        zoom={1}
      />
    </div>
  );
}
