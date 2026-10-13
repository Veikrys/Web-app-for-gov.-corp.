require('dotenv').config();
const express = require('express');
const mysql = require('mysql2/promise');

const app = express();
const PORT = process.env.PORT || 5000;

app.use(express.json());

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

app.listen(PORT, async () => {
  console.log(`Backend запущен на порту ${PORT}`);
  try {
    await pool.getConnection();
    console.log('✅ Подключение к MySQL успешно');
  } catch (err) {
    console.error('❌ Ошибка подключения к MySQL:', err.message);
  }
});