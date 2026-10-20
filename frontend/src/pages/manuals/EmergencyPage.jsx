import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Памятки при аварийных ситуациях</h3>
    <ul>
      <li><a href="/data/OIS/reglament_av.doc">📜 Регламент по авариям (.DOC)</a></li>
      <li><a href="/data/OIS/alarm_plan3.pdf">📜 Порядок действий в случае аварии в работе АСФК (.PDF)</a></li>
    </ul>

    <h3>Команда Менеджеров инцидентов ЦАФК</h3>
    <p>Команда МИ ЦАФК на постоянной основе доступна по многоканальному номеру телефона ВТС: <br /><b><span className="pre_phone">📞 0-(9500)-</span>5281 с 00:00 (понедельник) до 08:00 (субботы) по московскому времени</b>, а также по электронной почте <a href="mailto:9500-mi@roskazna.ru">📧 9500-mi@roskazna.ru</a></p>
    <p>В обязанности МИ входит устранение конфликтных ситуаций, возникающих в ходе выполнения заявок в СУЭ ЦАФК по всем федеральным ИТ-сервисам Федерального казначейства, и в том числе связанных с функционированием Государственной интегрированной информационной системы управления общественными финансами «Электронный бюджет».</p>
    <p>Для ускорения выполнения заявки в СУЭ ЦАФК, если срок планируемого выполнения не устраивает по критичности бизнес-функций Управления или по другим веским причинам, ответственные сотрудники или МИ Управления могут обращаться к МИ ЦАФК для повышения приоритета поданной заявки, в соответствие со «Стандартами обслуживания ИТ-систем ФК», с обязательным детальным и аргументированным объяснением причин необходимости повышения приоритета, кроме того в случае не выполнения Исполнителем или командой сопровождения заявок в СУЭ ЦАФК в срок или предоставления некачественного решения необходимо любым доступным способом:</p>
    <ul>
      <li>эскалацией заявки из системы управления эксплуатацией Управления в СУЭ ЦАФК;</li>
      <li>через Портал самообслуживания СУЭ (приоритетный способ обращения для сотрудников Управлений Московского региона, работающих в СУЭ ЦАФК);</li>
      <li>отправкой письма в свободной форме на адрес <a href="mailto:777@roskazna.local">📧 «СУЭ. Регистрация обращений»</a> или <a href="mailto:777@roskazna.local">📧 777@roskazna.local</a> в сети ЗКВС;</li>
      <li>звонком оператору по многоканальному телефону:
        <ol>
          <li>📞 ВТС <span className="pre_phone">0-(9500)-</span>5911;</li>
          <li>📞 городской номер телефона <span className="pre_phone">8-(495)-</span>214-70-20.</li>
        </ol>
      </li>
      <li>обратиться в Диспетчерскую службу ЦАФК для регистрации жалобы, которая будет назначена на Менеджеров инцидентов ЦАФК.</li>
    </ul>

    <h3>Контакты Менеджеров сервиса ГИИС ЭБ</h3>
    <p>Просьба обращаться к данным сотрудникам в случаях экстренной необходимости повышения приоритета или затягивания сроков решения обращений влияющих на критические бизнес процессы согласно НПА.</p>
    <div className="vt_info_wrapper">
      <table className="vt_info">
        <tbody>
          <tr>
            <td>ЕПБС</td>
            <td className="fullname_l">Митинская</td>
            <td>Екатерина</td>
            <td>Владимировна</td>
            <td><a href="mailto:Emitinskaya@roskazna.ru">Emitinskaya@roskazna.ru</a></td>
            <td><a href="mailto:MitinskayaEV@fsfk.local">MitinskayaEV@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-73-41</td>
            <td><span className="pre_phone">0-(9500)-</span>5621</td>
          </tr>
          <tr>
            <td>ПВБУ (1С)</td>
            <td className="fullname_l">Фирсов</td>
            <td>Константин</td>
            <td>Андреевич</td>
            <td><a href="mailto:kfirsov@roskazna.ru">kfirsov@roskazna.ru</a></td>
            <td><a href="mailto:FirsovKA@fsfk.local">FirsovKA@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-96-08</td>
            <td><span className="pre_phone">0-(9500)-</span>7908</td>
          </tr>
          <tr>
            <td>ПИАО</td>
            <td className="fullname_l">Дергачева</td>
            <td>Вероника</td>
            <td>Борисовна</td>
            <td><a href="mailto:vdergacheva@roskazna.ru">vdergacheva@roskazna.ru</a></td>
            <td><a href="mailto:DergachevaVB@fsfk.local">DergachevaVB@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-96-92</td>
            <td><span className="pre_phone">0-(9500)-</span>7992</td>
          </tr>
          <tr>
            <td>ПУДС, ПУР КС</td>
            <td className="fullname_l">Гадиятова</td>
            <td>Наталья</td>
            <td>Васильевна</td>
            <td><a href="mailto:ngadiyatova@roskazna.ru">ngadiyatova@roskazna.ru</a></td>
            <td><a href="mailto:GadiyatovaNV@fsfk.local">GadiyatovaNV@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-96-82</td>
            <td><span className="pre_phone">0-(9500)-</span>7982</td>
          </tr>
          <tr>
            <td>ПУИО</td>
            <td className="fullname_l">Шувалова</td>
            <td>Ольга</td>
            <td>Михайловна</td>
            <td><a href="mailto:omshuvalova@roskazna.ru">omshuvalova@roskazna.ru</a></td>
            <td><a href="mailto:ShuvalovaOM@fsfk.local">ShuvalovaOM@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-73-48</td>
            <td><span className="pre_phone">0-(9500)-</span>5558</td>
          </tr>
          <tr>
            <td>ПУР</td>
            <td className="fullname_l">Иванова</td>
            <td>Елена</td>
            <td>Эдуардовна</td>
            <td><a href="mailto:eivanova2@roskazna.ru">eivanova2@roskazna.ru</a></td>
            <td><a href="mailto:IvanovaEE@fsfk.local">IvanovaEE@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-75-34</td>
            <td><span className="pre_phone">0-(9500)-</span>5588</td>
          </tr>
          <tr>
            <td rowSpan={2}>НСИ</td>
            <td className="fullname_l">Орлова</td>
            <td>Ирина</td>
            <td>Игоревна</td>
            <td><a href="mailto:iorlova@roskazna.ru">iorlova@roskazna.ru</a></td>
            <td><a href="mailto:OrlovaII@fsfk.local">OrlovaII@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-70-67</td>
            <td><span className="pre_phone">0-(9500)-</span>5257</td>
          </tr>
          <tr>
            <td className="fullname_l">Перьев</td>
            <td>Константин</td>
            <td>Георгиевич</td>
            <td><a href="mailto:kperev@roskazna.ru">kperev@roskazna.ru</a></td>
            <td><a href="mailto:PerevKG@fsfk.local">PerevKG@fsfk.local</a></td>
            <td><span className="pre_phone">8-(495)-</span>214-76-49</td>
            <td><span className="pre_phone">0-(9500)-</span>5826</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h3>Контактные данные Менеджеров сервисов</h3>
    <div className="vt_info_wrapper">
      <table className="vt_info">
        <thead>
          <tr>
            <th colSpan={3}>Фамилия, Имя, Отчество</th>
            <th>Адрес электронной почты ЗКВС</th>
            <th>Адрес электронной почты Интернет</th>
            <th>Контактный телефон</th>
            <th>Сервис</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td className="fullname_l">Леонтьев</td>
            <td>Дмитрий</td>
            <td>Александрович</td>
            <td><a href="mailto:LeontevDmA@fsfk.local">LeontevDmA@fsfk.local</a></td>
            <td><a href="mailto:dmleontev@roskazna.ru">dmleontev@roskazna.ru</a></td>
            <td><span className="pre_phone">7 (495)</span> 214-96-93 (доб.&nbsp;7993)</td>
            <td>АСФК, ПУДС ЭБ</td>
          </tr>
          <tr>
            <td className="fullname_l">Слепцов</td>
            <td>Сергей</td>
            <td>Васильевич</td>
            <td><a href="mailto:SlepcovSN@fsfk.local">SlepcovSN@fsfk.local</a></td>
            <td><a href="mailto:sslepcov@roskazna.ru">sslepcov@roskazna.ru</a></td>
            <td><span className="pre_phone">7 (495)</span> 214-71-11 (доб.&nbsp;1311)</td>
            <td>ГИС ГМП, ГАСУ, ГИС ГМУ</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
);

export default function EmergencyPage() {
  return <ManualPage pageId="emergency" content={content} />;
}
