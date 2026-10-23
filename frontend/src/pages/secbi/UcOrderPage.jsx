import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Порядок реализации функций Удостоверяющего центра Федерального казначейства</h3>
    <p>
      <a href="/data/sec/doc21-02.pdf" className="undb">
        <span>📜 Порядок реализации Федеральным казначейством функций аккредитованного Удостоверяющего центра (.PDF)</span>
      </a>
    </p>
    <p style={{ marginTop: '1.5rem', padding: '1rem', background: 'var(--bg-submenu)', borderRadius: '8px', fontSize: '0.95rem', color: 'var(--text-secondary)' }}>
      🔗 Ссылка на документ будет добавлена дополнительно.
    </p>
  </div>
);

export default function SecbiUcOrderPage() {
  return <ManualPage pageId="secbi-uc-order" content={content} />;
}
