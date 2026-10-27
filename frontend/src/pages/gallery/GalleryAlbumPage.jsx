import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';
import { useTheme } from '../../context/ThemeContext';
import './GalleryPage.css';

const ALBUMS = [
  { id: 3750, name: 'Дети — цветы жизни', icon: '👶' },
  { id: 3751, name: 'Конкурс новогодних открыток', icon: '🎄' },
  { id: 3752, name: 'Здравствуй, школа!', icon: '🏫' },
  { id: 3753, name: 'Моя Калуга', icon: '🏛️' },
  { id: 3754, name: 'Новый год в моей семье', icon: '🎅' },
  { id: 3755, name: 'Мастерицы Казначейства', icon: '🧶' },
  { id: 3756, name: 'Конкурс детских поделок', icon: '🎨' },
  { id: 3757, name: 'Золотая осень', icon: '🍂' },
  { id: 3758, name: 'Новогодняя игрушка', icon: '🎀' },
  { id: 3759, name: 'Весна в моем доме', icon: '🌷' },
  { id: 3760, name: 'Повтори картину', icon: '🖼️' },
  { id: 3761, name: 'Конкурс «Моя Россия»', icon: '🇷🇺' },
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

export default function GalleryAlbumPage() {
  const { ofk } = useParams();
  const navigate = useNavigate();
  const { isDark } = useTheme();
  const [photos, setPhotos] = useState([]);
  const [album, setAlbum] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [zoomImage, setZoomImage] = useState(null);

  useEffect(() => {
    // Find album info
    const albumInfo = ALBUMS.find((a) => a.id === parseInt(ofk, 10));
    setAlbum(albumInfo || { name: `Отдел ${ofk}`, icon: '📷' });

    // Fetch photos
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
  }, [ofk]);

  const handleBack = () => {
    navigate('/gallery');
  };

  const handlePhotoClick = (photo) => {
    setZoomImage({
      src: `/img/photo20/${photo.file_full}`,
      alt: photo.description || '',
    });
  };

  if (loading) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <button className="gallery-back-btn" onClick={handleBack}>
            ← Назад к альбомам
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
            ← Назад к альбомам
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
