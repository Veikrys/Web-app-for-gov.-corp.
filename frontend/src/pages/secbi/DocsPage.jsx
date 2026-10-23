import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Перечень<br />служебных записок и документов,<br />представляемых на сотрудника, принятого на работу в Управление<br />в отдел режима секретности и безопасности информации</h3>
    
    <ul className="doclink">
      <li><a href="/data/RSiBI/doc_2026-02.doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Служебная записка о&nbsp;выдаче дистрибутивов СКЗИ, ключевых носителей и&nbsp;их&nbsp;регистрации. (.DOC)</span></a></li>
      <li><a href="/data/RSiBI/doc_2019-01(z_zk).doc" className="undb" style={{ color: 'var(--accent-color)' }}><span>📜 Заявка на предоставление сотруднику доступа к информационным ресурсам, программным и техническим средствам объекта вычислительной техники АСФК ЗК. (.DOC)</span></a></li>
    </ul>
  </div>
);

export default function SecbiDocsPage() {
  return <ManualPage pageId="secbi-docs" content={content} />;
}
