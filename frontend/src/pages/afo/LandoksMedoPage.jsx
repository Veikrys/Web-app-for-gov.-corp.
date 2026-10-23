import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Подготовка исходящего документа в ППО «Ландокс» для отправки по МЭДО.</h3>
    
    <p>Для подготовки исходящего документа и отправки его по МЭДО выполнить следующие действия:</p>
    
    <p style={{ fontWeight: 'bold' }}>1.&nbsp;Уточнить у сотрудника канцелярии, используется ли в Управлении документооборот по МЭДО с организацией, в которую вы хотите отправить документ с использованием МЭДО!</p>
    
    <p>2.&nbsp;В журнале регистрации «Проекты исходящей корреспонденции» создать проект документа.</p>
    
    <p>3.&nbsp;В открывшемся окне заполнить поля:</p>
    <ul style={{ textAlign: 'left' }}>
      <li>в поле «Способ доставки» указать <span style={{ fontWeight: 'bold', textDecoration: 'underline' }}>&laquo;МЭДО 2.7.1&raquo;</span>.</li>
      <li>в поле «Организация» выбрать <span style={{ fontWeight: 'bold', textDecoration: 'underline' }}>&laquo;абонента МЭДО&raquo;</span>.</li>
    </ul>
    
    <p>4.&nbsp;Загрузить файл/ файлы документа. Сопроводительное письмо /письмо (если нет приложений) делается на электронном бланке (аналогично документам, отравляемым в ФК или другие УФК по ППО &laquo;Ландокс&raquo;).</p>
    
    <p>5.&nbsp;После присоединения файла документа завизировать проект документа (порядок визирования - обычный).</p>
    
    <p>6.&nbsp;После визирования и <span style={{ fontWeight: 'bold', color: 'red' }}>перед подписанием</span> необходимо выполнить конвертацию главного файла (сопроводительное письмо) в формат PDF/A, нажав кнопку на ленте меню.</p>
    
    <p>7.&nbsp;Далее отправить проект документа на подписание, выбрав тип сообщения <span style={{ fontWeight: 'bold', textDecoration: 'underline' }}>&laquo;На подписание МЭДО&raquo;</span>.</p>
    
    <p>8.&nbsp;Подписанный проект документа направить сообщением с типом <span style={{ fontWeight: 'bold', textDecoration: 'underline' }}>&laquo;Для отправки по МЭДО&raquo;</span> <span style={{ textDecoration: 'underline' }}>&laquo;в адрес любого сотрудника канцелярии, находящегося на рабочем месте&raquo;</span>.</p>
    
    <p>9.&nbsp;Сообщение <span style={{ fontWeight: 'bold' }}>&laquo;На перерегистрацию в документ&raquo;</span> отправлять <span style={{ fontWeight: 'bold', color: 'red' }}>не нужно</span>.</p>
    
    <p style={{ fontStyle: 'italic' }}>Если указанные требования не соблюдены - документ отправлен по МЭДО не будет!</p>
  </div>
);

export default function AfoLandoksMedoPage() {
  return <ManualPage pageId="afo-landoks-medo" content={content} />;
}
