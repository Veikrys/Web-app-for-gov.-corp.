import { useEffect, useState, useMemo, useRef } from 'react';
import './HallOfFame.css';

export default function HallOfFame() {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [currentPair, setCurrentPair] = useState([null, null]);
  const [visible, setVisible] = useState(true);

  const pairsRef = useRef([]);

  // Разбиваем всех сотрудников на пары
  const pairs = useMemo(() => {
    const result = [];
    for (let i = 0; i < favorites.length; i += 2) {
      result.push([favorites[i], favorites[i + 1]?.ID_S ? favorites[i + 1] : null]);
    }
    return result;
  }, [favorites]);

  // Обновляем реф при изменении пар
  useEffect(() => {
    pairsRef.current = pairs;
  }, [pairs]);

  // Определяем начальную пару на основе секунд
  const initialPairIndex = useMemo(() => {
    if (pairs.length === 0) return 0;
    const secondsPerPair = 60 / pairs.length;
    const now = new Date();
    const seconds = now.getSeconds();
    return Math.floor(seconds / secondsPerPair) % pairs.length;
  }, [pairs]);

  // Циклическая смена пар с анимацией
  useEffect(() => {
    if (pairs.length === 0) return;

    let currentIndex = initialPairIndex;
    let timerId = null;

    const showPair = (index) => {
      const p = pairs[index];
      setCurrentPair([p[0], p[1]]);
      setVisible(true);
    };

    const updatePair = () => {
      setVisible(false);
      timerId = setTimeout(() => {
        currentIndex = (currentIndex + 1) % pairs.length;
        showPair(currentIndex);
      }, 800);
    };

    // Начальная пара
    showPair(currentIndex);

    const interval = setInterval(updatePair, 4000);

    return () => {
      clearInterval(interval);
      clearTimeout(timerId);
    };
  }, [pairs, initialPairIndex]);

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

  if (loading) {
    return (
      <aside className="hall-of-fame" aria-label="Доска почета">
        <h2 className="hall-of-fame-title">Доска почета</h2>
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
      <aside className="hall-of-fame" aria-label="Доска почета">
        <h2 className="hall-of-fame-title">Доска почета</h2>
        <p className="hall-of-fame-error">Не удалось загрузить данные: {error}</p>
      </aside>
    );
  }

  if (favorites.length === 0) {
    return (
      <aside className="hall-of-fame" aria-label="Доска почета">
        <h2 className="hall-of-fame-title">Доска почета</h2>
        <p className="hall-of-fame-empty">В данный момент данные не доступны</p>
      </aside>
    );
  }

  return (
    <aside className="hall-of-fame" aria-label="Доска почета">
      <h2 className="hall-of-fame-title">Доска почета</h2>
      <div className="hall-of-fame-carousel">
        {[0, 1].map((slotIndex) => {
          const person = currentPair[slotIndex];
          if (!person) return null;
          const uniqueKey = person.ID_S != null
            ? `person-${person.ID_S}`
            : `fallback-${slotIndex}-${person.FamilyName}-${person.FirstName}`;
          return (
            <div
              key={uniqueKey}
              className={`hall-of-fame-card ${visible ? 'visible' : 'hidden'}`}
              role="article"
              aria-label={`${person.FamilyName} ${person.FirstName} ${person.SecondName}`}
            >
              <div className="hall-of-fame-photo-wrapper">
                <img
                  src={`/img/person/${person.photo || 'default.png'}`}
                  alt={`${person.FamilyName} ${person.FirstName} ${person.SecondName}`}
                  className="hall-of-fame-photo"
                  loading="lazy"
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
        })}
      </div>
    </aside>
  );
}
