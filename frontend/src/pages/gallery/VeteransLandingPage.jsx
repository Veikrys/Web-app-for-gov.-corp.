import { useNavigate } from 'react-router-dom';
import './VeteransLandingPage.css';

export default function VeteransLandingPage() {
  const navigate = useNavigate();

  return (
    <div className="veterans-page">
      <div className="veterans-header">
        <h2 className="veterans-title">
          <span className="veterans-title-icon">🎖️</span>
          Мы — наследники Победы!
        </h2>
      </div>

      <div className="veterans-hero">
        
        <div className="veterans-hero-text">
          <p className="veterans-hero-desc">
            Молодёжный Совет проводит акцию <strong>«Мы — наследники Победы!»</strong>,
            приуроченную к празднованию Дня Победы. Давайте вспомним наших дедов и прадедов,
            чья молодость пришлась на фронтовые годы.
          </p>
          <p className="veterans-hero-desc">
            Мы хотим, чтобы все увидели лица тех, кто защищал нашу Родину в грозные годы войны.
            Просим всех желающих предоставить фото и информацию о родственниках,
            участвовавших в Великой Отечественной Войне.
          </p>
        </div>
      </div>

      {/* Блок скачивания анкеты */}
      <div className="veterans-download-card">
        <div className="veterans-download-icon">📄</div>
        <div className="veterans-download-info">
          <h3 className="veterans-download-title">Анкета ветерана</h3>
          <p className="veterans-download-desc">
            Для участия необходимо предоставить фотографию родственника,
            которая будет отсканирована и возвращена, а также анкету ветерана.
          </p>
        </div>
        <a
          href="/data/anketa_veterana.doc"
          className="veterans-download-btn"
          download
          target="_blank"
          rel="noopener noreferrer"
        >
          ⬇ Скачать
        </a>
      </div>

      {/* Вложенные альбомы */}
      <div className="veterans-sub-albums">
        <h3 className="veterans-sub-title">Страницы акции</h3>
        <div className="veterans-sub-grid">
          <div
            className="veterans-sub-tile"
            onClick={() => navigate('/gallery/veterans/photos')}
            role="button"
            tabIndex={0}
            aria-label="Открыть фотографии участников"
            onKeyDown={(e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                navigate('/gallery/veterans/photos');
              }
            }}
          >
            <div className="veterans-sub-tile-icon">📷</div>
            <div className="veterans-sub-tile-name">Фотографии участников</div>
            <div className="veterans-sub-tile-desc">
              Фотографии и информация о родственниках — участниках войны
            </div>
          </div>

          <div
            className="veterans-sub-tile"
            onClick={() => navigate('/gallery/veterans/posters')}
            role="button"
            tabIndex={0}
            aria-label="Открыть плакаты"
            onKeyDown={(e) => {
              if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                navigate('/gallery/veterans/posters');
              }
            }}
          >
            <div className="veterans-sub-tile-icon">🎨</div>
            <div className="veterans-sub-tile-name">Плакат</div>
            <div className="veterans-sub-tile-desc">
              Агитационные плакаты акции «Мы — наследники Победы!»
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
