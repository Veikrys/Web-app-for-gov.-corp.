import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3 className="hlp">Образцы заявлений</h3>
    <ul className="attach_list">
      <li><a href="/data/GGSiK/kadr_zv/zv_perevod.doc" className="undb"><span>📜 Заявление на перевод</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_perevod-k.doc" className="undb"><span>📜 Заявление на перевод по конкурсу</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_priem.doc" className="undb"><span>📜 Заявление на прием&nbsp;–&nbsp;государственные гражданские служащие</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_priem-t.doc" className="undb"><span>📜 Заявление на прием&nbsp;–&nbsp;государственные гражданские служащие (временно, на время отсутствия)</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_priem-mop.doc" className="undb"><span>📜 Заявление на прием&nbsp;–&nbsp;обслуживающий персонал</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_prod.doc" className="undb"><span>📜 Заявление на продление после 60 лет</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_uvl.doc" className="undb"><span>📜 Заявление на увольнение</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_konkurs.doc" className="undb"><span>📜 Заявление на участие в конкурсе</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_privl.doc" className="undb"><span>📜 Заявление на привлечение к работе в нерабочие дни</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_pril-do.doc" className="undb"><span>📜 Заявление на предоставление дня отдыха за работу в нерабочие дни</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpusk.doc" className="undb"><span>📜 Заявление на ежегодный отпуск&nbsp;–&nbsp;один период</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpusk-p.doc" className="undb"><span>📜 Заявление на ежегодный отпуск&nbsp;–&nbsp;несколько частей</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpust-pr.doc" className="undb"><span>📜 Заявление на перенос отпуска</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpusk-bsds.doc" className="undb"><span>📜 Заявление на предоставление отпуска без сохранения денежного содержания</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpusk-ch.doc" className="undb"><span>📜 Заявление на предоставление отпуска по беременности и родам</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_otpusk-ch3.doc" className="undb"><span>📜 Заявление на предоставление отпуска по уходу за ребенком до 3-х лет</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_mp.doc" className="undb"><span>📜 Заявление на материальную помощь</span></a></li>
      <li><a href="/data/GGSiK/kadr_zv/zv_kk.doc" className="undb"><span>📜 Заявление в комиссию по конфликтам</span></a></li>
    </ul>
  </div>
);

export default function GgsikDeclarationsPage() {
  return <ManualPage pageId="ggsik-declarations" content={content} />;
}
