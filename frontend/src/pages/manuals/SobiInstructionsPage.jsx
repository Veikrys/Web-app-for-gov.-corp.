import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Руководства ПОИБ СОБИ ФК</h3>
    <ul>
      <li><a href="/data/SEC/sobi_01/sobi_01.docx">📜 Руководство пользователя по подготовке АРМ (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_01/sobi_02.docx">📜 Руководство пользователя Сотрудника (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_01/sobi_03.docx">📜 Руководство пользователя Главного регистратора организации (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_01/sobi_04.docx">📜 Руководство пользователя Регистратора (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_05.docx">📜 Руководство пользователя Аудитора (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_01/sobi_06.docx">📜 Руководство пользователя Визирующего (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_08.docx">📜 Руководство пользователя Оператора СОБИ (.DOCX)</a></li>
      <li><a href="/data/SEC/sobi_09.docx">📜 Руководство пользователя Оператора ИИС (.DOCX)</a></li>
    </ul>

    <h3>Предоставление доступа ПОИБ СОБИ ФК</h3>
    <ul>
      <li><a href="/data/SEC/sobi_10.pdf">📜 Порядок предоставления доступа ПОИБ СОБИ ФK (.PDF)</a></li>
    </ul>
  </div>
);

export default function SobiInstructionsPage() {
  return <ManualPage pageId="sobi-instructions" content={content} />;
}
