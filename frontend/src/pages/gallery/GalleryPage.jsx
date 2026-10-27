import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './GalleryPage.css';

const ALBUMS = [
  { id: 3750, name: 'Дети — цветы жизни', icon: '👶' },
  { id: 3751, name: 'Конкурс новогодних открыток', icon: '🎄' },
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
    const album = dbAlbums.find((a) => a.ofk === ofk);
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
        {/* Альбом "Конкурсные фото УФК и ОФК" */}
        <div
          className="album-tile album-tile-konkurs"
          onClick={() => navigate('/gallery/konkurs')}
          role="button"
          tabIndex={0}
          aria-label="Открыть альбом Конкурсные фото УФК и ОФК"
          onKeyDown={(e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              navigate('/gallery/konkurs');
            }
          }}
        >
          <div className="album-tile-icon">📸</div>
          <div className="album-tile-name">Конкурсные фото УФК и ОФК</div>
          <div className="album-tile-count">13 подальбомов</div>
        </div>

        {/* Альбом "Мы — наследники Победы!" */}
        <div
          className="album-tile album-tile-veterans"
          onClick={() => navigate('/gallery/veterans')}
          role="button"
          tabIndex={0}
          aria-label="Открыть альбом Мы - наследники Победы"
          onKeyDown={(e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              navigate('/gallery/veterans');
            }
          }}
        >
          <div className="album-tile-icon">🎖️</div>
          <div className="album-tile-name">Мы — наследники Победы!</div>
          <div className="album-tile-count">Акция ко Дню Победы</div>
        </div>

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
              Конкурс фотографий
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
