// LDAP-утилита для подключения к контроллеру домена FSFK.local
// Получает полные ФИО сотрудников по доменному логину (samaccountname).
// Кэширует результаты в памяти, чтобы не обращаться к LDAP при каждом запросе.

const { connect, search, close } = require('ldapts');

// ---------- Конфигурация ----------

const LDAP_HOST = process.env.LDAP_HOST || 'S3700DC01.fsfk.local';
const LDAP_PORT = parseInt(process.env.LDAP_PORT, 10) || 389;
const LDAP_BASE_DN = process.env.LDAP_BASE_DN || 'DC=fsfk,DC=local';
const LDAP_SERVICE_USER = process.env.LDAP_SERVICE_USER || 'FSFK\\CHERNIKOVAV';
const LDAP_SERVICE_PASSWORD = process.env.LDAP_SERVICE_PASSWORD || '';

// ---------- Кэш ФИО ----------
// Map<login (uppercase), fullName | null>
const fullNameCache = new Map();

// Максимальный размер кэша
const MAX_CACHE_SIZE = 5000;

// ---------- Основной функционал ----------

/**
 * Получить полное ФИО сотрудника по доменному логину.
 * @param {string} userKey — полный ключ пользователя, например "FSFK\\CHERNIKOVAV" или "CHERNIKOVAV"
 * @returns {Promise<string|null>} — ФИО (displayName) или null, если не найдено
 */
async function getFullNameFromAD(userKey) {
  if (!userKey || !LDAP_SERVICE_PASSWORD) {
    return null;
  }

  // Извлекаем samaccountname из полного ключа
  const parts = String(userKey).split('\\');
  const login = parts[parts.length - 1].toUpperCase();
  if (!login) return null;

  // Проверяем кэш
  if (fullNameCache.has(login)) {
    return fullNameCache.get(login);
  }

  try {
    // Авторизуемся
    const conn = await connect({
      url: `ldap://${LDAP_HOST}:${LDAP_PORT}`,
      bindDN: LDAP_SERVICE_USER,
      bindPassword: LDAP_SERVICE_PASSWORD,
      timeout: 5000,
      connectTimeout: 5000,
    });

    try {
      // Поиск по samaccountname
      const filter = `(&(objectClass=user)(sAMAccountName=${login}))`;
      const entries = await search(conn, LDAP_BASE_DN, {
        filter,
        scope: 'sub',
        attributes: ['displayName', 'sn', 'givenName', 'cn'],
        timeout: 5000,
      });

      let fullName = null;

      if (entries && entries.length > 0) {
        const entry = entries[0];
        const displayName = Array.isArray(entry.displayName) ? entry.displayName[0] : entry.displayName;
        const sn = Array.isArray(entry.sn) ? entry.sn[0] : entry.sn;
        const givenName = Array.isArray(entry.givenName) ? entry.givenName[0] : entry.givenName;
        const cn = Array.isArray(entry.cn) ? entry.cn[0] : entry.cn;

        if (displayName) {
          fullName = displayName;
        } else if (sn && givenName) {
          fullName = `${sn} ${givenName}`;
        } else if (cn) {
          fullName = cn;
        }
      }

      // Сохраняем в кэш (null тоже кэшируем)
      fullNameCache.set(login, fullName || null);

      // Ограничиваем размер кэша
      if (fullNameCache.size > MAX_CACHE_SIZE) {
        const keys = Array.from(fullNameCache.keys());
        keys.slice(0, Math.floor(MAX_CACHE_SIZE / 4)).forEach((k) => fullNameCache.delete(k));
      }

      return fullName || null;
    } finally {
      await close(conn);
    }
  } catch (err) {
    console.error('[LDAP] Ошибка запроса к контроллеру домена:', err.message);
    // При ошибке кэшируем null на короткое время
    fullNameCache.set(login, null);
    return null;
  }
}

/**
 * Получить ФИО для нескольких пользователей (batch с кэшем).
 * @param {string[]} userKeys — массив доменных ключей
 * @returns {Promise<Map<string, string|null>>>} — Map<login, fullName>
 */
async function resolveFullNames(userKeys) {
  const results = new Map();

  const pending = [];
  for (const key of userKeys) {
    if (!key) continue;
    const parts = String(key).split('\\');
    const login = parts[parts.length - 1].toUpperCase();
    if (fullNameCache.has(login)) {
      results.set(login, fullNameCache.get(login));
    } else {
      pending.push(key);
    }
  }

  if (pending.length > 0) {
    const batch = await Promise.all(pending.map(getFullNameFromAD));
    pending.forEach((key, i) => {
      const parts = String(key).split('\\');
      const login = parts[parts.length - 1].toUpperCase();
      results.set(login, batch[i]);
    });
  }

  return results;
}

/**
 * Очистить кэш (полезно при перезапуске или смене конфигурации).
 */
function clearCache() {
  fullNameCache.clear();
}

module.exports = {
  getFullNameFromAD,
  resolveFullNames,
  clearCache,
};
