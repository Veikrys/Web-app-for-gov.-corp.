import ManualPage from '../../components/ManualPage/ManualPage';

const content = (
  <div className="manual-content">
    <h3>Инструкция по удаленному подключению почты домена roskazna.ru</h3>
    <p>Удаленное подключение почты может осуществляться следующими способами:</p>
    <ul>
      <li>через WEB браузер</li>
      <li>через удаленное подключение Outlook</li>
      <li>через мобильное устройство</li>
    </ul>
    
    <h4>Подключение к почте через WEB браузер (на основе IE)</h4>
    <ol>
      <li>В строке Web браузера набрать следующий путь: <a href="https://mail.roskazna.ru/owa/">🔗 https://mail.roskazna.ru/owa/</a></li>
      <li>В появившемся окне ввести имя домена\имя почтового ящика и пароль:
        <img src="/img/OIS/Roskazna/ext_mail01.png" alt="Ввод логина и пароля" className="manual-img" />
      </li>
      <li>Нажать ОК и войти на WEB страницу Outlook Web Access</li>
    </ol>
    
    <h4>Подключение к почте через удаленное подключение Outlook (на основе MS Outlook 2007)</h4>
    <p><b>Создание нового профиля</b></p>
    <ol>
      <li>Ввести почтовый адрес пользователя (вводится почтовый адрес пользователя, например, testmail@roskazna.ru):
        <img src="/img/OIS/Roskazna/ext_mail02.png" alt="Ввод почтового адреса" className="manual-img" />
      </li>
      <li>Ввести пароль пользователя (вводится логин пользователя, например roskazna\testmail и пароль из интернет сети):
        <img src="/img/OIS/Roskazna/ext_mail03.png" alt="Ввод пароля" className="manual-img" />
        <br />
        и дождаться настройки профиля:
        <img src="/img/OIS/Roskazna/ext_mail04.png" alt="Настройка профиля" className="manual-img" />
      </li>
      <li>После создания учётной записи запустить Outlook.</li>
    </ol>
    
    <hr />
    
    <p><b>Изменение имеющегося профиля и проверка настроек</b></p>
    <ol>
      <li>Для использования Outlook anywhere включить Подключение к Microsoft Exchange по протоколу http (путь: Outlook\Сервис\Настройка учетных записей…\ Изменить\Другие настройки\Подключение):
        <img src="/img/OIS/Roskazna/ext_mail05.png" alt="Настройка учетных записей" className="manual-img" />
      </li>
      <li>Ввести параметры подключения на рисунке ниже:
        <img src="/img/OIS/Roskazna/ext_mail06.png" alt="Параметры подключения" className="manual-img" />
      </li>
    </ol>
    
    <h4>Подключение к почте через мобильное устройство (на основе HTC)</h4>
    <ol>
      <li>Создать почтовую учётную запись, Exchange ActiveSync. (По умолчанию программа называется «Почта»).</li>
      <li>Далее выбрать «ручная настройка»:</li>
      <li>указать ваш внешний электронный адрес.
        <ol type="a">
          <li>Пример: Адрес эл. почты: user@roskazna.ru</li>
        </ol>
      </li>
      <li>В строке адрес сервера указать: mail.roskazna.ru</li>
      <li>В строке домен указать: roskazna.ru</li>
      <li>В строке имя пользователя указать, ваш цифровой логин используемый для работы в сети интернет. Пример: Имя пользователя: 2670.</li>
      <li>В строке пароль, ввести пароль от учётной используемый для работы в сети интернет.</li>
      <li>Установить галочку «Использовать защищённое подключение SSL».</li>
      <li>После установления связи с сервером, необходимо выбрать данные для синхронизации с сервером:
        <ol type="a">
          <li>Почта</li>
          <li>Контакты</li>
          <li>Календарь мероприятий</li>
        </ol>
      </li>
      <li>Нажать «Готово».</li>
    </ol>
  </div>
);

export default function RoskaznaMailPage() {
  return <ManualPage pageId="roskazna-mail" content={content} />;
}
