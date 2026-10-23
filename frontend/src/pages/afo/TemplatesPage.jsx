import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Шаблоны документов</h3>
    
    <ul style={{ textAlign: 'left', listStyleImage: 'url(/img/word_doc.png)' }}>
      <li><a href="/data/AFO/prikaz.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон приказа (.DOC)</span></a></li>
      <li><a href="/data/AFO/prikaz_zm.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон приказа (замещение) (.DOC)</span></a></li>
      <li><a href="/data/AFO/prikaz_izm.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон приказа (о внесении изменений) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_zam.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма (заместители руководителя) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_io.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма (и.о. руководителя) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_erukpr.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма электронный (руководитель, с приложениями) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_erukzg.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма электронный (руководитель, с заголовком) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_erukbz.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма электронный (руководитель, без заголовка) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_ezam.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма электронный (заместители руководителя) (.DOC)</span></a></li>
      <li><a href="/data/AFO/letter_eio.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Шаблон письма электронный (и. о. руководителя) (.DOC)</span></a></li>
    </ul>
  </div>
);

export default function AfoTemplatesPage() {
  return <ManualPage pageId="afo-templates" content={content} />;
}
