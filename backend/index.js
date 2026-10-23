require('dotenv').config();
const express = require('express');
const mysql = require('mysql2/promise');

const app = express();
const PORT = process.env.PORT || 5000;

app.use(express.json());

// Опросы
app.use('/api/polls', require('./polls'));

// Подключение к MySQL (локальная установка)
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'ufk',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  charset: 'utf8mb4'
});

// Тестовый маршрут
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', message: 'Backend работает!' });
});

// Тест подключения к MySQL
app.get('/api/db-test', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT NOW() as now');
    res.json({ success: true, time: rows.now });
  } catch (err) {
    console.error('Ошибка подключения к БД:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Получить список таблиц
app.get('/api/tables', async (req, res) => {
  try {
    const [tables] = await pool.query('SHOW TABLES');
    res.json({ success: true, tables: tables.map(t => t[Object.keys(t)[0]]) });
  } catch (err) {
    console.error('Ошибка получения таблиц:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Доска почета — сотрудники с максимальным фаворитом
app.get('/api/hall-of-fame', async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT
        u.ID_S,
        u.FamilyName,
        u.FirstName,
        u.SecondName,
        d.Dep_Name,
        s.Staff_Name,
        u.photo,
        u.ofk,
        o.Region,
        o.Name
      FROM ufk u
      INNER JOIN departament d ON u.id_d = d.ID_D
      INNER JOIN staff s ON u.id_s = s.ID_S
      LEFT JOIN ofk o ON u.ofk = o.ID_O
      WHERE u.fav = (SELECT MAX(fav) FROM ufk)
      ORDER BY u.FamilyName, u.FirstName
    `);
    res.json(rows);
  } catch (err) {
    console.error('Ошибка получения доски почета:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Количество пользователей онлайн
app.get('/api/active-users', async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT COUNT(DISTINCT client_ip) AS count
      FROM access
      WHERE time >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    `);
    res.json({ count: rows[0].count || 0 });
  } catch (err) {
    console.error('Ошибка получения статистики:', err);
    res.status(500).json({ count: 0 });
  }
});

// === Фотогалерея ===

// Получить список альбомов галереи
app.get('/api/gallery/albums', async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT
        k.ID_K,
        k.name,
        k.type,
        (
          SELECT COUNT(*)
          FROM photo20 p
          WHERE p.id_k = k.ID_K
            AND p.ofk IN (3750,3751,3752,3753,3754,3755,3756,3757,3758,3759,3760,3761)
        ) AS photo_count
      FROM konkurs k
      WHERE k.active > 0
      ORDER BY k.name
    `);
    res.json(rows);
  } catch (err) {
    console.error('Ошибка получения альбомов галереи:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Получить фотографии конкретного альбома
app.get('/api/gallery/album/:ofk', async (req, res) => {
  try {
    const { ofk } = req.params;
    const [rows] = await pool.query(`
      SELECT
        p.ID_P,
        p.id_k,
        p.ofk,
        p.date_photo,
        p.file_small,
        p.file_full,
        p.description,
        p.width_small,
        p.height_small,
        p.width_full,
        p.height_full,
        p.Comment,
        p.tag,
        p.fav,
        p.time_post,
        COALESCE(AVG(v.mark), 0) AS s_m,
        COUNT(v.mark) AS c_m,
        COALESCE(SUM(v.mark), 0) AS n_m
      FROM photo20 p
      LEFT JOIN photo20_vote v ON p.ID_P = v.id_p
      WHERE p.ofk = ?
      GROUP BY p.ID_P
      ORDER BY p.fav DESC, p.time_post DESC
    `, [ofk]);
    res.json(rows);
  } catch (err) {
    console.error('Ошибка получения фотографий альбома:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

app.listen(PORT, async () => {
  console.log(`Backend запущен на порту ${PORT}`);
  try {
    await pool.getConnection();
    console.log('✅ Подключение к MySQL успешно');
  } catch (err) {
    console.error('❌ Ошибка подключения к MySQL:', err.message);
  }
});