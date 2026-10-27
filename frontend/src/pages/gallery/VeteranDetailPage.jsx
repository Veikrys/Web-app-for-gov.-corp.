import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import ImageZoomModal from '../../components/ImageZoomModal/ImageZoomModal';
import { useTheme } from '../../context/ThemeContext';
import './VeteransLandingPage.css';

const MONTHS_RU = [
  '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
  'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
];

function formatDate(dateStr) {
  if (!dateStr) return '';
  const day = dateStr.slice(8, 10);
  const month = parseInt(dateStr.slice(5, 7), 10);
  const year = dateStr.slice(0, 4);
  return `${day} ${MONTHS_RU[month]} ${year} г.`;
}

export default function VeteranDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { isDark } = useTheme();
  const [veteran, setVeteran] = useState(null);
  const [photos, setPhotos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [zoomImage, setZoomImage] = useState(null);

  useEffect(() => {
    fetch(`/api/veterans/detail/${id}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setVeteran(data.veteran);
          setPhotos(data.photos || []);
        } else {
          setError(data.error || 'Ошибка загрузки данных');
        }
        setLoading(false);
      })
      .catch((err) => {
        console.error('Ошибка загрузки ветерана:', err);
        setError('Не удалось загрузить информацию о ветеране');
        setLoading(false);
      });
  }, [id]);

  const handleBack = () => {
    navigate('/gallery/veterans/photos');
  };

  const handlePhotoClick = (photoSrc) => {
    setZoomImage({ src: photoSrc, alt: '' });
  };

  if (loading) {
    return (
      <div className="veterans-page">
        <div className="veterans-header">
          <button className="veterans-back-btn" onClick={handleBack}>
            ← Назад
          </button>
          <h2 className="veterans-title">
            <span className="veterans-title-icon">🎖️</span>
            Загрузка…
          </h2>
        </div>
        <div className="veterans-loading">Загрузка информации о ветеране…</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="veterans-page">
        <div className="veterans-header">
          <button className="veterans-back-btn" onClick={handleBack}>
            ← Назад
          </button>
          <h2 className="veterans-title">
            <span className="veterans-title-icon">🎖️</span>
            Информация о ветеране
          </h2>
        </div>
        <div className="veterans-error">{error}</div>
      </div>
    );
  }

  if (!veteran) {
    return (
      <div className="veterans-page">
        <div className="veterans-header">
          <button className="veterans-back-btn" onClick={handleBack}>
            ← Назад
          </button>
          <h2 className="veterans-title">
            <span className="veterans-title-icon">🎖️</span>
            Ветеран не найден
          </h2>
        </div>
      </div>
    );
  }

  return (
    <div className="veterans-page">
      <div className="veterans-header">
        <button className="veterans-back-btn" onClick={handleBack}>
          ← Назад
        </button>
        <h2 className="veterans-title">
          <span className="veterans-title-icon">🎖️</span>
          Информация о ветеране
        </h2>
      </div>

      {/* Основная информация */}
      <div className="veteran-detail-info">
        <table className="veteran-detail-table">
          <tbody>
            <tr>
              <td className="veteran-detail-num">1.</td>
              <td className="veteran-detail-label">Фамилия, имя, отчество ветерана</td>
              <td className="veteran-detail-value">
                <b>{veteran.v_fmn}</b> {veteran.v_fsn} {veteran.v_sdn}
              </td>
            </tr>
            <tr className="veteran-detail-alt">
              <td className="veteran-detail-num">2.</td>
              <td className="veteran-detail-label">Фамилия, имя, отчество сотрудника УФК</td>
              <td className="veteran-detail-value">
                <b>{veteran.u_fmn}</b> {veteran.u_fsn} {veteran.u_sdn}
              </td>
            </tr>
            <tr>
              <td className="veteran-detail-num">3.</td>
              <td className="veteran-detail-label">Степень родства</td>
              <td className="veteran-detail-value">{veteran.v_fml}</td>
            </tr>
            <tr className="veteran-detail-alt">
              <td className="veteran-detail-num">4.</td>
              <td className="veteran-detail-label">Сколько лет было в 1941 г.</td>
              <td className="veteran-detail-value">{veteran.v_age}</td>
            </tr>
            <tr>
              <td className="veteran-detail-num">5.</td>
              <td className="veteran-detail-label">Период участия в Войне</td>
              <td className="veteran-detail-value">{veteran.v_prd}</td>
            </tr>
            <tr className="veteran-detail-alt">
              <td className="veteran-detail-num">6.</td>
              <td className="veteran-detail-label">Фронт, армия</td>
              <td className="veteran-detail-value">{veteran.v_amy}</td>
            </tr>
            <tr>
              <td className="veteran-detail-num">7.</td>
              <td className="veteran-detail-label">Род войск</td>
              <td className="veteran-detail-value">{veteran.v_cps}</td>
            </tr>
            <tr className="veteran-detail-alt">
              <td className="veteran-detail-num">8.</td>
              <td className="veteran-detail-label">Сведения о наградах</td>
              <td className="veteran-detail-value">{veteran.v_ams}</td>
            </tr>
            <tr>
              <td className="veteran-detail-num">9.</td>
              <td className="veteran-detail-label">Дополнительная информация</td>
              <td className="veteran-detail-value">{veteran.v_inf}</td>
            </tr>
          </tbody>
        </table>
      </div>

      {/* Фотографии и документы */}
      {photos.length > 0 && (
        <div className="veteran-detail-photos">
          <h3 className="veteran-detail-photos-title">Фотографии и документы</h3>
          <div className="veteran-photos-grid">
            {photos.map((photo, idx) => (
              <div
                key={idx}
                className="veteran-photo-card"
                onClick={() => handlePhotoClick(`/img/photo70/${photo.PhotoFull}`)}
                role="button"
                tabIndex={0}
                aria-label="Посмотреть фото"
                onKeyDown={(e) => {
                  if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    handlePhotoClick(`/img/photo70/${photo.PhotoFull}`);
                  }
                }}
              >
                <div className="veteran-photo-card-img-wrap">
                  <img
                    src={`/img/photo70/${photo.PhotoFull}`}
                    alt={photo.Description || ''}
                    className="veteran-photo-card-img"
                    loading="lazy"
                  />
                </div>
                {photo.Description && (
                  <div className="veteran-photo-card-desc">{photo.Description}</div>
                )}
              </div>
            ))}
          </div>
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
