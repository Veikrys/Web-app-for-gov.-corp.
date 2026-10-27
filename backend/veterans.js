const express = require('express');
const router = express.Router();
const cleanHtml = require('./cleaner');

// Pool будет установлен из index.js
let pool;

router.init = (dbPool) => {
  pool = dbPool;
};

// Получить список ветеранов (для карточек)
router.get('/list', async (req, res) => {
  try {
    const [rows] = await pool.query(`
      SELECT
        v.ID_V AS v_idv,
        v.FamilyName AS v_fmn,
        v.FirstName AS v_fsn,
        v.SecondName AS v_sdn,
        up.PhotoSmall AS p_phs
      FROM veteran v
      INNER JOIN ufk u ON u.ID = v.id_u
      INNER JOIN veteran_photo up ON v.ID_V = up.id_v AND up.List = 1
      ORDER BY v.FamilyName, v.FirstName
    `);
    res.json(rows);
  } catch (err) {
    console.error('Ошибка получения списка ветеранов:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Получить детальную информацию о ветеране
router.get('/detail/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const [rows] = await pool.query(`
      SELECT
        v.ID_V AS v_idv,
        v.FamilyName AS v_fmn,
        v.FirstName AS v_fsn,
        v.SecondName AS v_sdn,
        v.FamilyLink AS v_fml,
        v.Age AS v_age,
        v.Period AS v_prd,
        v.Army AS v_amy,
        v.Corps AS v_cps,
        v.Arms AS v_ams,
        v.Info AS v_inf,
        u.ofk AS u_ofk,
        s.Staff_Name AS s_sfn,
        d.Dep_Name AS d_dpn,
        u.FamilyName AS u_fmn,
        u.FirstName AS u_fsn,
        u.SecondName AS u_sdn
      FROM veteran v
      INNER JOIN ufk u ON u.ID = v.id_u
      INNER JOIN staff s ON u.id_s = s.ID_S
      INNER JOIN departament d ON u.id_d = d.ID_D
      WHERE v.ID_V = ?
    `, [id]);

    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Ветеран не найден' });
    }

    // Получить фотографии ветерана
    const [photos] = await pool.query(`
      SELECT PhotoSmall, PhotoFull, Description, \`Order\`
      FROM veteran_photo
      WHERE id_v = ?
      ORDER BY \`Order\`
    `, [id]);

    const veteran = { ...rows[0] };

    const fieldsToClean = [
      'v_prd',
      'v_amy',
      'v_cps',
      'v_ams',
      'v_inf'
    ];

    for (const field of fieldsToClean) {
      veteran[field] = cleanHtml(veteran[field]);
    }

    res.json({ success: true, veteran, photos });
  } catch (err) {
    console.error('Ошибка получения информации о ветеране:', err);
    res.status(500).json({ success: false, error: err.message });
  }
});

// Получить список плакатов
router.get('/posters', async (req, res) => {
  try {
    // Плакаты хранятся как статические файлы
    const [rows] = await pool.query(`
      SELECT
        vp.ID_P AS p_id,
        vp.PhotoSmall,
        vp.PhotoFull,
        vp.Description,
        vp.\`Order\`
      FROM veteran_posters vp
      ORDER BY vp.\`Order\`
    `);
    res.json(rows);
  } catch (err) {
    // Если таблица не существует, вернуть статический список
    console.log('Таблица veteran_posters не найдена, используем статический список');
    res.json([]);
  }
});

module.exports = router;
