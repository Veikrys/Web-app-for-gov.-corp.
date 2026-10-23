import ManualPage from '../../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Обращения в Комиссию</h3>
    <p>Вы можете обратиться в Комиссию одним из следующих способов:</p>
    <ol style={{ textAlign: 'left', whiteSpace: 'normal' }}>
      <li>✉️ Письмом по почте. Направляйте Ваши письма по адресу: 248009, г. Калуга, Грабцевское шоссе, д. 39. Управление Федерального казначейства по Калужской области.</li>
      <li>📧 По электронной почте на адрес 37ufk@rambler.ru</li>
      <li>📞 По телефонной &quot;горячей линии&quot;:71-97-65 <i>(Карпушина Виктория Евгеньевна, ведущий специалист-эксперт отдела кадров)</i>, 71-97-77 <i>(Аскреткова Анна Владимировна, специалист 1 разряда административного отдела)</i>.</li>
    </ol>
  </div>
);

export default function ObrasheniePage() {
  return <ManualPage pageId="obrashenie" content={content} />;
}
