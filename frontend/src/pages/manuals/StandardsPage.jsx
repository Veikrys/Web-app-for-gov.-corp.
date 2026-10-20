import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Стандарты обслуживания ИТ-подсистем ФК:</h3>
    <ul>
      <li>
        <a href="../data/OIS/standarts_it.doc">
          <span> 📜 Стандарты обслуживания ИТ-систем ФК (.DOC)</span>
        </a>
      </li>
      <li><a href="../data/OIS/pril1.docx" ><span> 📜 Приложение 1. Перечень мероприятий, обязательных для выполнения сотрудниками УФК. (.DOCX)</span></a></li>
      <li><a href="../data/OIS/pril2.doc"><span> 📜 Приложение 2. Отчет о деятельности Отдела информационных систем УФК. (.DOC)</span></a></li>
      <li><a href="../data/OIS/pril3.doc"><span> 📜 Приложение 3. Памятка по проверке Паспорта ИТ-подсистем в УФК. (.DOC)</span></a></li>
      <li><a href="../data/OIS/letter_42-7.4-05_10.5-32.doc"><span> 📜 Письмо в УФК по реализации Стратегической карты на 2013-2017 (.DOC)</span></a></li>
      <li><a href="../data/OIS/reglament_ud.pdf"><span> 📜 Регламент предоставления удаленного доступа к информационным ресурсам ФК (.PDF)</span></a></li>
    </ul>
  </div>
);

export default function StandardsPage() {
  return <ManualPage pageId="standards" content={content} />;
}
