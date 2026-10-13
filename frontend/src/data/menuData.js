export const menuData = [
  {
    id: 'main-pages',
    title: 'Основные страницы сайта',
    items: [
      { label: 'Документы на нового сотрудника', url: 'depsec.php?doc=0' },
      { label: 'Отдел информационных систем', url: 'manuals.php' },
      { label: 'Отдел ГГСиК', url: 'depkadr.php' },
      { label: 'Административно-финансовый отдел', url: 'depadm.php' },
      { label: 'Отдел РСиБИ', url: 'depsec.php?doc=1' },
      { label: 'Фотоконкурсы Управления', url: 'konkurs.php' },
      { label: 'Акция «Мы — наследники Победы»', url: 'veterans.php' },
      { label: 'Телефонный справочник — клиенты', url: 'ubp.php', adminOnly: true },
      { label: 'Поиск по телефонной книге', url: 'search.php' },
      { label: 'Календарь', url: 'calendar.php' },
      {
        label: 'Опросы',
        url: '#',
        children: [
          { label: 'Менеджер опросов', url: 'polls_manager.php', requiresAuth: true },
        ],
      },
      { label: 'Ведение базы данных сертификатов', url: 'certif.php', adminOnly: true },
    ],
  },
  {
    id: 'management-resources',
    title: 'Ресурсы Управления',
    items: [
      { label: 'Форум УФК по Калужской области', url: 'http://10.37.1.44/phpbb3/' },
      { label: 'АСФК', url: 'http://a3700w12.ufk37.roskazna.local:7797/' },
      { label: 'АСФК (Брянск)', url: 'http://a2700w12.ufk27.roskazna.local:7797/' },
      { label: 'АСФК ЦОД ДУБНА ТЕСТ', url: 'http://a3700w12.ufk37.roskazna.local:7897/' },
      { label: 'СУФД', url: 'http://s3700w03.ufk37.roskazna.local:28081/' },
      { label: 'АРМ ОРФК', url: 'http://a3700w12.ufk37.roskazna.local:38081/', adminOnly: true },
      { label: 'АСД LanDocs', url: 'https://s0006ldzweb03.fsfk.local/' },
      { label: 'АСД LanDocs (Тест)', url: 'https://s0006ldztweb03.fsfk.local' },
      { label: 'Тестирование персонала УФК', url: 'http://10.37.1.54:85/' },
      { label: 'ИПС Гарант', url: 'https://s3700garantweb.fsfk.local/' },
      { label: 'ИПС КонсультантПлюс', url: 'https://s3700conspweb.fsfk.local/cons/' },
      { label: 'Почта Outlook', url: 'https://mail.fsfk.local/owa' },
    ],
  },
  {
    id: 'treasury-resources',
    title: 'Ресурсы ФК',
    items: [
      { label: 'Официальный сайт', url: 'https://kaluga.roskazna.gov.ru/' },
      { label: 'Ведомственный портал', url: 'https://vp.fsfk.local' },
      { label: 'Правовая работа', url: 'https://vp-pravo.cert.roskazna.ru/' },
      { label: 'Телефонный справочник ФК, МОУ, ЦОКР', url: 'https://disk.fsfk.local/s/DyHqzSj6PHdjYeQ' },

      // === Аксиок ===
      {
        label: 'Аксиок',
        url: '#',
        children: [
          { label: 'ИС «АКСИОК»', url: 'https://aksiok.cert.roskazna.ru/' },
          { label: 'Аксиок Планирование', url: 'http://aksiok.plan.fsfk.local' },
        ],
      },

      // === СУЭ ФК ===
      {
        label: 'СУЭ ФК',
        url: '#',
        children: [
          { label: 'СУЭ ФК (пользователь)', url: 'https://sm-sue.fsfk.local/portal/' },
          { label: 'СУЭ ФК (специалист)', url: 'https://sm-sue.fsfk.local/sd/operator/index.jsp' },
          { label: 'ПУПЭ Портал самообслуживания', url: 'http://s9500pupeblns04.fsfk.local/user' },
          { label: 'ПУПЭ Портал специалиста', url: 'http://s9500pupeblns04.fsfk.local/spec' },
          { label: 'СУЭ ФК — Обучающие материалы', url: 'https://peo.portal-fk.fsfk.local/course/view.php?id=147l' },
          { label: 'СУЭ ФК Инструкция диспетчера и эксперта', url: 'data/it/isue_d.pdf' },
          { label: 'СУЭ ФК Инструкция пользователя', url: 'data/it/isue_p.pdf' },
          { label: 'СУЭ ФК Инструкция специалиста', url: 'data/it/isue_s.pdf' },
        ],
      },

      // === СПТО и КПЭ ===
      {
        label: 'СПТО и КПЭ',
        url: '#',
        children: [
          { label: 'Сервер СПТО СБ', url: 'http://spto2.moufk.local:8050/' },
          { label: 'Сервер СПТО УК', url: 'http://spto1.moufk.local:88/ucon.service/jsp/index.jsp' },
          { label: 'Сервер КПЭ', url: 'http://kpe.fsfk.local/' },
        ],
      },

      // === Подсистема ВРС ===
      {
        label: 'Подсистема ВРС',
        url: '#',
        children: [
          { label: 'Портал Заявителя (ФЗС)', url: 'https://fzs.roskazna.ru/' },
          { label: 'Закрытая часть', url: 'https://vrs.cert.roskazna.ru/' },
        ],
      },

      { label: 'Ландокс ДСП', url: 'https://dsp.ld.roskazna.ru' },
      { label: 'АОКЗ', url: 'https://aokz.cert.roskazna.ru' },
      { label: 'ГИС ГМП', url: 'http://10.141.145.22:8080/gisgmp/' },
      { label: 'ГИС ГМП (новая)', url: 'https://armgisgmp.cert.roskazna.ru' },

      // === СКИАО ===
      {
        label: 'СКИАО',
        url: '#',
        children: [
          { label: 'СКИАО', url: 'https://skiao.cert.roskazna.ru' },
          { label: 'СКИАО Правовая работа', url: 'https://skiao.cert.roskazna.ru/pravo/' },
          { label: 'Мониторинг', url: 'https://eoi.cert.roskazna.ru/skiao/sd/Monitoring' },
          { label: 'Показатели ГГС', url: 'https://eoi.cert.roskazna.ru/skiao/ggs' },
          { label: 'ПСПД', url: 'https://skiao.cert.roskazna.ru/sd/RunGatherTofk' },
        ],
      },

      { label: 'Свод показателей БО по СГ', url: 'http://10.128.1.220:8889/sufdclient/index.zul' },
      { label: 'Свод-СМАРТ', url: 'http://10.128.1.206:83' },
      { label: 'АСП-Ревизор', url: 'https://eb.cert.roskazna.ru/PFKVGFK/application' },
      { label: 'Система функционального мониторинга ГИС ЖКХ', url: 'http://10.133.28.13:58080/analytics' },
      { label: 'Личный кабинет органа контроля', url: 'https://lk.zakupki.gov.ru/sso/secure' },

      // === Электронный бюджет ===
      {
        label: 'Электронный бюджет',
        url: '#',
        children: [
          { label: 'Электронный бюджет через ПОИБ СОБИ', url: 'https://eb.cert.roskazna.ru/' },
          { label: 'Электронный бюджет ЕПБС', url: 'https://eb.cert.roskazna.ru/epbs/' },
          { label: 'Электронный бюджет (тестовый)', url: 'https://ebzoggr.cert-test.roskazna.ru/' },

          // === Модули (вложенный подкаталог) ===
          {
            label: 'Модули',
            url: '#',
            children: [
              { label: 'ПУР', url: 'https://eb.cert.roskazna.ru/EXP/' },
              { label: 'ПУР 05', url: 'https://eb.cert.roskazna.ru/EXP05FB/' },
              { label: 'АУ БУ', url: 'https://eb.cert.roskazna.ru/INC/' },
              { label: 'ПУиО', url: 'https://eb.cert.roskazna.ru/ARP/' },
              { label: 'ПУР КС', url: 'https://eb.cert.roskazna.ru/TSE/' },
              { label: 'НСИ', url: 'https://eb.cert.roskazna.ru/REF/' },
              { label: 'ПУДС', url: 'https://eb.cert.roskazna.ru/FAMABS/' },
              { label: 'ПУДС касплан', url: 'https://rkkp.cert.roskazna.ru/' },
              { label: 'ПФК — Аналитические полномочия', url: 'https://pfk-ap.cert.roskazna.ru/' },
            ],
          },

          { label: 'ПИАО', url: 'https://eb.cert.roskazna.ru/piao/' },
          { label: 'ПУДС ОЕБС', url: 'http://puds.budget.gov.ru/OA_HTML/AppsLogin' },
          { label: 'Подсистема «КУБ»', url: 'http://lk.budget.gov.ru/dcs' },
          { label: 'ПФК — Аналитические полномочия', url: 'https://eb.cert.roskazna.ru/PFKAP/application' },
        ],
      },

      // === 1С Бухгалтерия ===
      {
        label: '1С Бухгалтерия',
        url: '#',
        children: [
          { label: '1С ГОСТ 2012', url: 'https://buh2012.budget.gov.ru/buh2012/' },
          { label: '1С МАУ 2012', url: 'https://buh2012.budget.gov.ru/buh2012/vis_akbu/1455/ru_RU/' },
        ],
      },

      { label: 'ПУДС КБР', url: 'https://10.141.129.6/#/signin' },
      { label: 'Электронное обучение', url: 'https://peo.portal-fk.fsfk.local' },
      { label: 'Бюджетный мониторинг', url: 'http://10.128.1.215/bmskp/index.php' },
      { label: 'ППМД СУЭ ФК', url: 'https://ppmd.fsfk.local/' },
      { label: 'ПОИБ СОБИ ФК', url: 'https://sobi.esia.roskazna.ru' },
      { label: 'ПОИБ СОБИ ФК (тест)', url: 'https://sobi.cert-dev.roskazna.ru' },
      { label: 'ЕБП ФИНАНСЫ', url: 'https://lk.finance.gov.ru/' },
      { label: 'ЕИС', url: 'https://zakupki.gov.ru/epz/main/public/home.html' },
      { label: 'ГМУ', url: 'https://bus.gov.ru/' },
      { label: 'FAP', url: 'https://cloud.fsfk.local' },
      { label: 'АРМ ОКС', url: 'https://s9500infohub02.fsfk.local/armoks/' },
      { label: 'Облачное хранилище', url: 'https://ufkdisk.fsfk.local/' },
      { label: 'Платформа для работы с ИИ', url: 'https://agent-navigator.fsfk.local:4000/chat' },
    ],
  },
];
