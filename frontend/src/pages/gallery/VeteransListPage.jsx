import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';
import { useTheme } from '../../context/ThemeContext';
import './VeteransLandingPage.css';

export default function VeteransListPage() {
  const navigate = useNavigate();
  const { isDark } = useTheme();
  const [veterans, setVeterans] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [zoomImage, setZoomImage] = useState(null);

  useEffect(() => {
    fetch('/api/veterans/list')
      .then((res) => res.json())
      .then((data) => {
        setVeterans(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error('Ошибка загрузки ветеранов:', err);
        setError('Не удалось загрузить фотографии участников');
        setLoading(false);
      });
  }, []);

  const handleBack = () => {
    navigate('/gallery/veterans');
  };

  const handleVeteranClick = (id) => {
    navigate(`/gallery/veterans/detail/${id}`);
  };

  const handlePhotoClick = (e, photoSrc) => {
    e.stopPropagation();
    setZoomImage({ src: photoSrc, alt: '' });
  };

  if (loading) {
    return (
      <div className="veterans-page">
        <div className="veterans-header">
          <h2 className="veterans-title">
            <span className="veterans-title-icon">📷</span>
            Фотографии участников
          </h2>
        </div>
        <div className="veterans-loading">Загрузка фотографий…</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="veterans-page">
        <div className="veterans-header">
          <h2 className="veterans-title">
            <span className="veterans-title-icon">📷</span>
            Фотографии участников
          </h2>
        </div>
        <div className="veterans-error">{error}</div>
      </div>
    );
  }

  return (
    <div className="veterans-page">
      <div className="veterans-header">
        <h2 className="veterans-title">
          <span className="veterans-title-icon">📷</span>
          Фотографии участников
        </h2>
        <div className="veterans-photo-count">{veterans.length} участников</div>
      </div>

      {veterans.length === 0 ? (
        <div className="veterans-empty">В этом альбоме пока нет фотографий</div>
      ) : (
        <div className="veterans-grid">
          {veterans.map((veteran) => (
            <div
              key={veteran.v_idv}
              className="veteran-card"
              onClick={() => handleVeteranClick(veteran.v_idv)}
              role="button"
              tabIndex={0}
              aria-label={`Открыть страницу ветерана`}
              onKeyDown={(e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                  e.preventDefault();
                  handleVeteranClick(veteran.v_idv);
                }
              }}
            >
              <div className="veteran-card-img-wrap">
                <img
                  src={`/img/photo70/${veteran.p_phs}`}
                  alt={`${veteran.v_fmn} ${veteran.v_fsn}`}
                  className="veteran-card-img"
                  loading="lazy"
                />
                <button
                  className="veteran-card-zoom-btn"
                  onClick={(e) => handlePhotoClick(e, `/img/photo70/${veteran.p_phs}`)}
                  aria-label="Увеличить фото"
                >
                  🔍
                </button>
              </div>
              <div className="veteran-card-info">
                <div className="veteran-card-name">
                  <b>{veteran.v_fmn}</b> {veteran.v_fsn} {veteran.v_sdn}
                </div>
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
