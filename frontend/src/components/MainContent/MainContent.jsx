import { useRef, useState } from 'react';
import ImageZoomModal from '../ImageZoomModal/ImageZoomModal';
import './MainContent.css';

const orgTexts = [
  'Указом Президента Российской Федерации № 1556 от 8 декабря 1992 года и Постановлением Правительства № 864 от 27 августа 1993 года в составе Министерства финансов РФ было создано Федеральное казначейство. Органы федерального казначейства как система были созданы для того, чтобы управлять денежными средствами на счетах казначейства и осуществлять предварительный и текущий контроль за целевым использования средств бюджета, начиная от доведения лимитов бюджетных обязательств и заканчивая кассовым расходом.',
  'С 16 июня 2006 года Руководителем Управления Федерального казначейства по Калужской области назначен Николай Петрович Хвостенко.',
  'Управление Федерального казначейства по Калужской области является составной частью единой централизованной системы органов федерального казначейства.',
  'Управление Федерального казначейства по Калужской области расположено по адресу: 248009, г. Калуга, Грабцевское шоссе, д. 39',
];

export default function MainContent() {
  const buildingImgRef = useRef(null);
  const [zoomImage, setZoomImage] = useState(null);

  return (
    <main className="main-content" role="main">
      <section className="org-info" aria-label="Об управлении">
        <h2 className="org-info-title">Об Управлении</h2>
        <div className="org-info-media">
          <div className="org-info-image-wrapper" ref={buildingImgRef}>
            <img
              src="/img/ufk_building.jpg"
              alt="Здание УФК по Калужской области"
              className="org-info-image"
              onClick={() => setZoomImage({
                src: '/img/ufk_building.jpg',
                alt: 'Здание УФК по Калужской области',
              })}
              loading="lazy"
            />
          </div>
          <div className="org-info-text">
            {orgTexts.map((text, index) => (
              <p key={index} className="org-info-paragraph">{text}</p>
            ))}
          </div>
        </div>
        <div className="org-info-chronicle">
          <a href="/data/svl_22_3.docx" className="chronicle-link" target="_blank" rel="noopener noreferrer">
            <span className="chronicle-icon" aria-hidden="true">📜</span>
            Летопись Управления (.PDF)
          </a>
        </div>
      </section>

      <ImageZoomModal image={zoomImage} onClose={() => setZoomImage(null)} />
    </main>
  );
}
