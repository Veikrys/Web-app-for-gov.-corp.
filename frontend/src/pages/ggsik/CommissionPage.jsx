import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Комиссия по соблюдению требований к служебному поведению<br />и урегулированию конфликта интересов.</h3>
    <ul style={{ textAlign: 'left' }}>
      <li><a href="/ggsik/legal-acts/prikaz-7n" className="undb"><span>Приказ Федерального казначейства от 12 июля 2011 г. <b>№ 7н</b>.</span></a></li>
      <li><a href="/ggsik/commission/prikaz-2n" className="undb"><span>Приказ Федерального казначейства от 4 июня 2008 г. <b>№ 2н</b>.</span></a></li>
      <li><a href="/ggsik/commission/sostav-komissii" className="undb"><span>Состав комиссии.</span></a></li>
      <li><a href="/ggsik/commission/obrashenie" className="undb"><span>Обращение в комиссию.</span></a></li>
      <li><a href="/ggsik/legal-acts/prikaz-283" className="undb"><span>Кодекс этики и служебного поведения федеральных государственных гражданских служащих Федерального казначейства, утвержденный приказом Федерального казначейства от 14.07.2011г..</span></a></li>
      <li><a href="/data/GGSiK/komis/35_73017.TIF" className="undb"><span>Приказ УФК по Калужской области от 20 октября 2010г. <b>№ 184</b> <i>&ldquo;Об утверждении Правил передачи подарков, полученных федеральными государственными гражданскими служащими Управления Федерального казначейства по Калужской области в связи с протокольными мероприятиями, служебными командировками и другими официальными мероприятиями&rdquo;</i>.</span></a></li>
      <li><a href="/ggsik/commission/prikaz-360" className="undb"><span>Приказ Управления Федерального казначейства по Калужской области от 28.08.2012 <b>№ 360</b> <i>&ldquo;Об утверждении Плана противодействия коррупции в Управлении Федерального казначейства по Калужской области на 2012-2013 годы&rdquo;</i>.</span></a></li>
      <li><a href="/ggsik/commission/otchet-2012" className="undb"><span>Отчет Комиссии по соблюдению требований к служебному поведению федеральных государственных гражданских служащих аппарата Управления Федерального казначейства по Калужской области (за исключением руководителя и заместителей руководителя Управления) и урегулированию конфликта интересов за 2012 год.</span></a></li>
    </ul>
  </div>
);

export default function GgsikCommissionPage() {
  return <ManualPage pageId="ggsik-commission" content={content} />;
}
