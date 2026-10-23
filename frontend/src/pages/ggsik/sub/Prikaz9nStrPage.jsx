import ManualPage from '../../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Приказ Федерального казначейства от 31.08.2010 <b>№ 9н</b> <i>&ldquo;Об утверждении Положения об организации и проведении служебных проверок в отношении федеральных государственных гражданских служащих Федерального казначейства&rdquo;</i>.</h3>
    <p>Текст документа отсутствует в исходных материалах.</p>
  </div>
);

export default function Prikaz9nStrPage() {
  return <ManualPage pageId="prikaz-9n-sluzebnaya-proverka" content={content} />;
}
