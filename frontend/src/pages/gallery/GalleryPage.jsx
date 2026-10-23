import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './GalleryPage.css';

// Маппинг ID_K из БД → ofk (ID отдела)
const ID_K_TO_OFK = {
  2: 3750, // Дети-цветы жизни
  3: 3751, // Конкурс новогодних открыток
  4: 3752, // Здравствуй, школа!
  5: 3753, // Моя Калуга
  6: 3754, // Новый Год в моей семье
  7: 3755, // Мастерицы Казначейства
  8: 3756, // Конкурс детских поделок
  9: 3757, // Золотая осень
  10: 3758, // Новогодняя игрушка
  11: 3759, // Весна в моем доме
  12: 3760, // Повтори картину
  13: 3761, // Конкурс «Моя Россия»
};

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

export default function GalleryPage() {
  const navigate = useNavigate();
  const [dbAlbums, setDbAlbums] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch('/api/gallery/albums')
      .then((res) => res.json())
      .then((data) => {
        setDbAlbums(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error('Ошибка загрузки альбомов:', err);
        setError('Не удалось загрузить альбомы');
        setLoading(false);
      });
  }, []);

  const getPhotoCount = (ofk) => {
    const idK = ID_K_TO_OFK[ofk];
    if (!idK) return 0;
    const album = dbAlbums.find((a) => a.ID_K === idK);
    return album ? album.photo_count : 0;
  };

  const handleAlbumClick = (ofk) => {
    navigate(`/gallery/${ofk}`);
  };

  if (loading) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <h2 className="gallery-title">Фотогалерея</h2>
        </div>
        <div className="gallery-loading">Загрузка альбомов…</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="gallery-page">
        <div className="gallery-header">
          <h2 className="gallery-title">Фотогалерея</h2>
        </div>
        <div className="gallery-error">{error}</div>
      </div>
    );
  }

  return (
    <div className="gallery-page">
      <div className="gallery-header">
        <h2 className="gallery-title">Фотогалерея</h2>
      </div>
      <div className="gallery-grid">
        {ALBUMS.map((album) => (
          <div
            key={album.id}
            className="album-tile"
            onClick={() => handleAlbumClick(album.id)}
            role="button"
            tabIndex={0}
            aria-label={`Открыть альбом ${album.name}`}
            onKeyDown={(e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handleAlbumClick(album.id);
              }
            }}
          >
            <div className="album-tile-icon">{album.icon}</div>
            <div className="album-tile-name">{album.name}</div>
            <div className="album-tile-count">
              {getPhotoCount(album.id)} {getPhotoCount(album.id) === 1 ? 'фото' : 'фото'}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
