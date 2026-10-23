// Подключение к контроллеру домена (Active Directory) для получения ФИО по логину.
// Все настройки — в переменных окружения, пароль в коде не хранится:
//   LDAP_URL          ldap://S3700DC01.fsfk.local:389
//   LDAP_BASE_DN      DC=fsfk,DC=local
//   LDAP_BIND_DN      FSFK\SERVICE_ACCOUNT   (учётка с правом чтения AD)
//   LDAP_BIND_PASSWORD
// Если LDAP_URL не задан или сервер недоступен — возвращается логин (как в старом сайте при ошибке AD).
const { Client } = require('ldapts');

const config = {
  url: process.env.LDAP_URL,
  baseDN: process.env.LDAP_BASE_DN,
  bindDN: process.env.LDAP_BIND_DN,
  bindPassword: process.env.LDAP_BIND_PASSWORD,
};

const enabled = !!(config.url && config.baseDN && config.bindDN && config.bindPassword);

// Кэш ФИО: логин → ФИО. Живёт, пока работает процесс.
const nameCache = new Map();

const escapeFilter = (s) => s.replace(/[\\*()\0]/g, (c) => '\\' + c.charCodeAt(0).toString(16).padStart(2, '0'));

async function lookupName(login) {
  const client = new Client({ url: config.url, timeout: 5000, connectTimeout: 5000 });
  try {
    await client.bind(config.bindDN, config.bindPassword);
    const { searchEntries } = await client.search(config.baseDN, {
      scope: 'sub',
      filter: `(sAMAccountName=${escapeFilter(login)})`,
      attributes: ['displayName', 'sn', 'givenName'],
      sizeLimit: 1,
    });
    const entry = searchEntries[0];
    if (!entry) return null;
    const first = (v) => (Array.isArray(v) ? v[0] : v);
    if (entry.displayName) return String(first(entry.displayName));
    if (entry.sn && entry.givenName) return `${first(entry.sn)} ${first(entry.givenName)}`;
    return null;
  } finally {
    await client.unbind().catch(() => {});
  }
}

// Возвращает ФИО для логина (вида "FSFK\LOGIN" или "LOGIN"); при ошибке — сам логин
async function getFullName(userKey) {
  const login = String(userKey).split('\\').pop();
  if (!enabled) return login;
  const key = login.toUpperCase();
  if (nameCache.has(key)) return nameCache.get(key);
  try {
    const name = (await lookupName(login)) || login;
    nameCache.set(key, name);
    return name;
  } catch (err) {
    console.error(`LDAP: не удалось получить ФИО для ${login}:`, err.message);
    return login; // не кэшируем ошибку — попробуем снова при следующем запросе
  }
}

// Пакетное получение ФИО: уникальные логины, параллельно
async function getFullNames(userKeys) {
  const unique = [...new Set(userKeys)];
  const names = await Promise.all(unique.map(getFullName));
  return new Map(unique.map((u, i) => [u, names[i]]));
}

module.exports = { getFullName, getFullNames, ldapEnabled: enabled };
