import { useEffect, useState, useMemo, useRef } from 'react';
import ImageZoomModal from '../ImageZoomModal/ImageZoomModal';
import './HallOfFame.css';

export default function HallOfFame() {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [collapsed, setCollapsed] = useState(true);
  const [currentPair, setCurrentPair] = useState(null);
  const [visible, setVisible] = useState(true);
  const [zoomedImage, setZoomedImage] = useState(null);

  // Разбиваем всех сотрудников на пары
  const pairs = useMemo(() => {
    const result = [];
    for (let i = 0; i < favorites.length; i += 2) {
      result.push([favorites[i], favorites[i + 1] || null]);
    }
    return result;
  }, [favorites]);

  const pairsRef = useRef([]);
  const indexRef = useRef(0);
  const intervalRef = useRef(null);

  // Загрузка данных
  useEffect(() => {
    const fetchFavorites = async () => {
      try {
        setLoading(true);
        const response = await fetch('/api/hall-of-fame');
        if (!response.ok) throw new Error('Ошибка загрузки');
        const data = await response.json();
        setFavorites(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchFavorites();
  }, []);

  // Карусель: работает только когда свёрнуто (collapsed === true)
  useEffect(() => {
    if (pairs.length === 0) {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
        intervalRef.current = null;
      }
      return;
    }

    // Если раскрыто — останавливаем карусель
    if (!collapsed) {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
        intervalRef.current = null;
      }
      return;
    }

    pairsRef.current = pairs;

    // Останавливаем старый интервал
    if (intervalRef.current) {
      clearInterval(intervalRef.current);
    }

    // Случайная начальная пара при каждом запуске
    indexRef.current = Math.floor(Math.random() * pairs.length);

    const showPair = (index) => {
      const p = pairs[index];
      setCurrentPair([p[0], p[1]]);
      setVisible(true);
    };

    const updatePair = () => {
      setVisible(false);
      setTimeout(() => {
        indexRef.current = (indexRef.current + 1) % pairs.length;
        showPair(indexRef.current);
      }, 1000); // 1 секунда на анимацию затухания
    };

    // Показываем первую пару сразу
    showPair(indexRef.current);

    // Смена каждые 4 секунды
    intervalRef.current = setInterval(updatePair, 5000);

    return () => {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
        intervalRef.current = null;
      }
    };
  }, [pairs, collapsed]);

  const toggle = () => setCollapsed(prev => !prev);

  const handleZoom = (person) => {
    const photoPath = person.photo ? `/img/person/${person.photo}` : '/img/person/default.png';
    const fullName = `${person.FamilyName} ${person.FirstName} ${person.SecondName}`;
    setZoomedImage({ src: photoPath, alt: fullName });
  };

  const renderPersonCard = (person, uniqueKey, withAnimation, onZoom) => {
    if (!person) return null;
    return (
      <div
        key={uniqueKey}
        className={`hall-of-fame-card ${withAnimation && visible ? 'visible' : ''} ${withAnimation && !visible ? 'hidden' : ''}`}
        role="article"
        aria-label={`${person.FamilyName} ${person.FirstName} ${person.SecondName}`}
      >
        <div className="hall-of-fame-photo-wrapper">
          <img
            src={`/img/person/${person.photo || 'default.png'}`}
            alt={`${person.FamilyName} ${person.FirstName} ${person.SecondName}`}
            className="hall-of-fame-photo"
            loading="lazy"
            onClick={onZoom ? () => onZoom(person) : undefined}
            style={onZoom ? { cursor: 'zoom-in' } : undefined}
            onError={(e) => { e.target.src = '/img/person/default.png'; }}
          />
        </div>
        <div className="hall-of-fame-info">
          <p className="hall-of-fame-name">
            <strong>{person.FamilyName}</strong> {person.FirstName} {person.SecondName}
          </p>
          <p className="hall-of-fame-position">{person.Staff_Name}</p>
          <p className="hall-of-fame-dept">
            {person.ofk === 3700 ? person.Dep_Name : (
              <>
                Отдел №{String(person.ofk).slice(2, 4).replace(/^0/, '')} (по {
                  person.Region !== 'району'
                    ? person.Name
                    : `${person.Name} району`
                })
              </>
            )}
          </p>
        </div>
      </div>
    );
  };

  if (loading) {
    return (
      <aside
        className={`hall-of-fame ${collapsed ? 'collapsed' : 'expanded'}`}
        aria-label="Доска почета"
        style={{ width: collapsed ? 180 : 520 }}
      >
        <h2
          className="hall-of-fame-title"
          onClick={toggle}
          role="button"
          tabIndex={0}
          onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') toggle(); }}
        >
          <span>Доска почета</span>
          <button
            className="hall-of-fame-toggle-btn"
            onClick={(e) => { e.stopPropagation(); toggle(); }}
            aria-label={collapsed ? 'Раскрыть' : 'Свернуть'}
          >
            {collapsed ? '→' : '←'}
          </button>
        </h2>
        <div className="hall-of-fame-carousel" aria-busy="true">
          {[1, 2].map(i => (
            <div key={i} className="hall-of-fame-card skeleton" aria-hidden="true">
              <div className="skeleton-photo"></div>
              <div className="skeleton-line skeleton-name"></div>
              <div className="skeleton-line skeleton-position"></div>
              <div className="skeleton-line skeleton-dept"></div>
            </div>
          ))}
        </div>
      </aside>
    );
  }

  if (error) {
    return (
      <aside
        className={`hall-of-fame ${collapsed ? 'collapsed' : 'expanded'}`}
        aria-label="Доска почета"
        style={{ width: collapsed ? 180 : 520 }}
      >
        <h2
          className="hall-of-fame-title"
          onClick={toggle}
          role="button"
          tabIndex={0}
          onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') toggle(); }}
        >
          <span>Доска почета</span>
          <button
            className="hall-of-fame-toggle-btn"
            onClick={(e) => { e.stopPropagation(); toggle(); }}
            aria-label={collapsed ? 'Раскрыть' : 'Свернуть'}
          >
            {collapsed ? '→' : '←'}
          </button>
        </h2>
        <p className="hall-of-fame-error">Не удалось загрузить данные: {error}</p>
      </aside>
    );
  }

  if (favorites.length === 0) {
    return (
      <aside
        className={`hall-of-fame ${collapsed ? 'collapsed' : 'expanded'}`}
        aria-label="Доска почета"
        style={{ width: collapsed ? 180 : 520 }}
      >
        <h2
          className="hall-of-fame-title"
          onClick={toggle}
          role="button"
          tabIndex={0}
          onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') toggle(); }}
        >
          <span>Доска почета</span>
          <button
            className="hall-of-fame-toggle-btn"
            onClick={(e) => { e.stopPropagation(); toggle(); }}
            aria-label={collapsed ? 'Раскрыть' : 'Свернуть'}
          >
            {collapsed ? '→' : '←'}
          </button>
        </h2>
        <p className="hall-of-fame-empty">В данный момент данные не доступны</p>
      </aside>
    );
  }

  return (
    <aside
      className={`hall-of-fame ${collapsed ? 'collapsed' : 'expanded'}`}
      aria-label="Доска почета"
      style={{ width: collapsed ? 180 : 520 }}
    >
      <h2
        className="hall-of-fame-title"
        onClick={toggle}
        role="button"
        tabIndex={0}
        onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') toggle(); }}
      >
        <span>Доска почета</span>
        <button
          className="hall-of-fame-toggle-btn"
          onClick={(e) => { e.stopPropagation(); toggle(); }}
          aria-label={collapsed ? 'Раскрыть' : 'Свернуть'}
        >
          {collapsed ? '→' : '←'}
        </button>
      </h2>

      {collapsed ? (
        /* ===== Карусель (свёрнуто) ===== */
        <div className="hall-of-fame-carousel">
          {[0, 1].map((slotIndex) => {
            const person = currentPair?.[slotIndex] || null;
            if (!person) return null;
            const uniqueKey = person.ID_S != null
              ? `person-${person.ID_S}`
              : `fallback-${slotIndex}-${person.FamilyName}-${person.FirstName}`;
            return renderPersonCard(person, uniqueKey, true);
          })}
        </div>
      ) : (
        /* ===== Сетка всех пар (раскрыто) ===== */
        <div className="hall-of-fame-grid">
          {pairs.map((pair, pairIndex) => (
            <div key={pairIndex} className="hall-of-fame-pair">
              {pair.map((person, slotIndex) => {
                if (!person) return null;
                const uniqueKey = person.ID_S != null
                  ? `person-${person.ID_S}`
                  : `fallback-${pairIndex}-${slotIndex}-${person.FamilyName}-${person.FirstName}`;
                return renderPersonCard(person, uniqueKey, false, handleZoom);
              })}
            </div>
          ))}
        </div>
      )}

      {zoomedImage && (
        <ImageZoomModal
          image={zoomedImage}
          onClose={() => setZoomedImage(null)}
          zoom={1.5}
        />
      )}
    </aside>
  );
}
