import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Формы и бланки.</h3>
    <ul className="attach_list">
      <li><a href="/data/GGSiK/fb/35_163364.doc" className="undb"><span>📜 Справка о расходах. (.DOC) <i> (утверждена Указом президента РФ от 02.04.13 № 310)</i></span></a></li>
      <li><a href="/data/GGSiK/fb/35_145905.doc" className="undb"><span>📜 Уведомление представителя нанимателя о намерении выполнять иную оплачиваемую работу. (.DOC)</span></a></li>
      <li><a href="/data/GGSiK/fb/35_145906.xls" className="undb"><span>📜 Справка о доходах, об имуществе и обязательствах имущественного характера гражданина, претендующего на замещение должности федеральной государственной службы. (.XLS)</span></a></li>
      <li><a href="/data/GGSiK/fb/35_145907.xls" className="undb"><span>📜 Справка о доходах, об имуществе и обязательствах имущественного характера супруги (супруга) и несовершеннолетних детей гражданина, претендующего на замещение должности федеральной государственной службы. (.XLS)</span></a></li>
      <li><a href="/data/GGSiK/fb/35_145908.xls" className="undb"><span>📜 Справка о доходах, об имуществе и обязательствах имущественного характера федерального государственного служащего. (.XLS)</span></a></li>
      <li><a href="/data/GGSiK/fb/35_145909.xls" className="undb"><span>📜 Справка о доходах, об имуществе и обязательствах имущественного характера супруги (супруга) и несовершеннолетних детей федерального государственного служащего. (.XLS)</span></a></li>
    </ul>
  </div>
);

export default function GgsikFormsPage() {
  return <ManualPage pageId="ggsik-forms" content={content} />;
}
