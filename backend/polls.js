// Модуль опросов (перенос с PHP: polls_save / polls_change / polls_vote / polls_unvote /
// polls_delete / polls_results / polls_export_csv).
// Хранилище — файлы, формат полностью совместим со старым сайтом:
//   <POLLS_DIR>/<id>.json          — опрос (с подсчитанными голосами)
//   <POLLS_DIR>/votes/<id>.json    — кто и как проголосовал
//   <POLLS_DIR>/index.json         — индекс созданных опросов
//   <POLLS_DIR>/deleted_polls/     — удалённые опросы
//   <POLLS_DIR>/changed_log.txt, deleted_log.txt — журналы
//   <POLLS_DIR>/users.json         — (необязательно) { "ЛОГИН": "ФИО" } для списка голосовавших
const express = require('express');
const fs = require('fs');
const path = require('path');

const POLLS_DIR = process.env.POLLS_DIR || path.join(__dirname, 'data', 'polls');
const VOTES_DIR = path.join(POLLS_DIR, 'votes');
const DELETED_DIR = path.join(POLLS_DIR, 'deleted_polls');

// Руководители: полный доступ ко всем опросам
const SUPERVISORS = ['FSFK\\SoldatovAN', 'FSFK\\SenichkinOV', 'FSFK\\ChernikovAV'];
// Менеджеры: просмотр результатов и «Поделиться»
const MANAGERS = [
  'FSFK\\VasinAN', 'FSFK\\AnisimovaAA', 'FSFK\\BalyberdinaNV', 'FSFK\\RyzhovaNN', 'FSFK\\SenichkinaOI',
  'FSFK\\HvostenkoNP', 'FSFK\\GlazkovaOG', 'FSFK\\KozlovaOA', 'FSFK\\KostylevaOV', 'FSFK\\AnikinaLN',
  'FSFK\\ErmakovaNA', 'FSFK\\VasilevaEV', 'FSFK\\ArtamonovaLL', 'FSFK\\BubnovaVL', 'FSFK\\AndryuhinaTG',
  'FSFK\\DvoryanchikovaTN', 'FSFK\\DocenkoDV', 'FSFK\\SeynerOI', 'FSFK\\BelovaOYU', 'FSFK\\KravchenkoNV',
  'FSFK\\BoyarovaPA', 'FSFK\\KorostelevIA', 'FSFK\\ZharkovaYUI', 'FSFK\\DmitrievaSV', 'FSFK\\ProdanPV',
  'FSFK\\SvechnikovaEV', 'FSFK\\ChistyakovaVV', 'FSFK\\ArseevaEYU', 'FSFK\\NosovaOV', 'FSFK\\ChuikinVYU',
  'FSFK\\ZaharovAV', 'FSFK\\AndrianovaEYU', 'FSFK\\KizinaNV', 'FSFK\\PlatonovVA', 'FSFK\\FinakinaEYU',
  'FSFK\\MedvedevaVS', 'FSFK\\GrebennikovVA', 'FSFK\\GorbachovaNV', 'FSFK\\AleshinaOV', 'FSFK\\KolyazinaEYU',
];

const norm = (s) => String(s || '').trim().toUpperCase();
const SUPERVISOR_SET = new Set(SUPERVISORS.map(norm));
const MANAGER_SET = new Set(MANAGERS.map(norm));

const router = express.Router();

// ---------- Вспомогательные функции ----------

function ensureDirs() {
  fs.mkdirSync(VOTES_DIR, { recursive: true });
}

function readJson(file, fallback) {
  try {
    return JSON.parse(fs.readFileSync(file, 'utf8').replace(new RegExp('^' + String.fromCharCode(0xFEFF)), ''));
  } catch {
    return fallback;
  }
}

function writeJson(file, data) {
  fs.writeFileSync(file, JSON.stringify(data, null, 4), 'utf8');
}

function appendLog(file, line) {
  fs.appendFileSync(path.join(POLLS_DIR, file), line + '\n', 'utf8');
}

const pollFile = (id) => path.join(POLLS_DIR, `${id}.json`);
const votesFile = (id) => path.join(VOTES_DIR, `${id}.json`);

// В старых данных часть счётчиков хранится строками ("0") — приводим к числам
function readPoll(id) {
  const poll = readJson(pollFile(id), null);
  if (!poll || !Array.isArray(poll.questions)) return null;
  poll.questions.forEach((q) => {
    q.votes = (q.answers || []).map((_, i) => Number(q.votes?.[i]) || 0);
  });
  return poll;
}

function readVotes(id) {
  const v = readJson(votesFile(id), []);
  return Array.isArray(v) ? v : [];
}

// «Дата окончания» хранится как "YYYY-MM-DDTHH:mm" в локальном времени сервера
const isActive = (poll) => new Date() <= new Date(poll.end_date);
const isValidDate = (s) => typeof s === 'string' && !Number.isNaN(new Date(s).getTime());

function getTechnicalTitle(fullTitle, maxLength = 60) {
  const title = String(fullTitle).trim();
  if (title.length <= maxLength) return title;
  const short = title.slice(0, maxLength);
  const lastSpace = short.lastIndexOf(' ');
  if (lastSpace > 0) return title.slice(0, lastSpace) + '…';
  return short + '…';
}

const TRANSLIT = {
  а: 'a', б: 'b', в: 'v', г: 'g', д: 'd', е: 'e', ё: 'yo', ж: 'zh', з: 'z', и: 'i', й: 'y', к: 'k',
  л: 'l', м: 'm', н: 'n', о: 'o', п: 'p', р: 'r', с: 's', т: 't', у: 'u', ф: 'f', х: 'h', ц: 'ts',
  ч: 'ch', ш: 'sh', щ: 'sch', ъ: '', ы: 'y', ь: '', э: 'e', ю: 'yu', я: 'ya',
};
function translit(title) {
  const out = [...String(title)]
    .map((ch) => {
      const lower = ch.toLowerCase();
      if (!(lower in TRANSLIT)) return ch;
      const t = TRANSLIT[lower];
      return ch === lower ? t : t.charAt(0).toUpperCase() + t.slice(1);
    })
    .join('');
  return out.replace(/[^a-zA-Z0-9_-]/g, '_');
}

// ---------- Пользователь и права ----------

// Windows-логин приходит от прокси в заголовке X-Remote-User (nginx затирает присланный клиентом).
// Для разработки без прокси можно задать POLLS_DEV_USER.
function getUser(req) {
  return norm(req.get('x-remote-user') || process.env.POLLS_DEV_USER || '');
}

const isSupervisor = (user) => SUPERVISOR_SET.has(user);
const isOwner = (poll, user) => !!user && norm(poll.creator) === user;
const canEdit = (poll, user) => isOwner(poll, user) || isSupervisor(user);
const canViewResults = (poll, user) => canEdit(poll, user) || MANAGER_SET.has(user);

let fullNames = null;
function displayName(userKey) {
  if (!fullNames) {
    const raw = readJson(path.join(POLLS_DIR, 'users.json'), {});
    fullNames = {};
    Object.entries(raw).forEach(([k, v]) => { fullNames[norm(k.split('\\').pop())] = v; });
  }
  const login = String(userKey).split('\\').pop();
  return fullNames[norm(login)] || login;
}

function requireUser(req, res, next) {
  const user = getUser(req);
  if (!user) return res.status(401).json({ error: 'Требуется аутентификация' });
  req.pollUser = user;
  next();
}

router.param('id', (req, res, next, id) => {
  if (!/^\d+$/.test(id)) return res.status(400).json({ error: 'Некорректный ID опроса' });
  const poll = readPoll(id);
  if (!poll) return res.status(404).json({ error: 'Опрос не найден' });
  req.poll = poll;
  req.pollId = id;
  next();
});

// ---------- Разбор вопросов из формы ----------

// questions: [{ text, type, answers: string[], origIndex? }]
// origIndex — индекс вопроса в сохранённом опросе (чтобы при редактировании сохранить голоса)
function parseQuestions(raw, oldPoll) {
  const result = [];
  (Array.isArray(raw) ? raw : []).forEach((q) => {
    const text = String(q?.text ?? '').trim();
    if (!text) return;
    const answers = (Array.isArray(q.answers) ? q.answers : [])
      .map((a) => String(a ?? '').trim())
      .filter(Boolean);
    if (!answers.length) return;

    let votes = answers.map(() => 0);
    const old = oldPoll && Number.isInteger(q.origIndex) ? oldPoll.questions[q.origIndex] : null;
    if (old) votes = answers.map((_, i) => old.votes[i] || 0);

    result.push({
      id: 'q' + (result.length + 1),
      text,
      type: q.type === 'multiple' ? 'multiple' : 'single',
      answers,
      votes,
    });
  });
  return result;
}

// ---------- Маршруты ----------

router.get('/me', (req, res) => {
  const user = getUser(req);
  res.json({ user, login: user.split('\\').pop(), authenticated: !!user });
});

router.get('/', (req, res) => {
  ensureDirs();
  const polls = fs.readdirSync(POLLS_DIR)
    .filter((f) => /^\d+\.json$/.test(f))
    .map((f) => readPoll(f.replace('.json', '')))
    .filter(Boolean)
    .sort((a, b) => Number(a.id) - Number(b.id))
    .map((p) => ({
      id: p.id,
      title: p.title,
      title_short: p.title_short || getTechnicalTitle(p.title),
      start_date: p.start_date,
      end_date: p.end_date,
      voting_type: p.voting_type || 'public',
      active: isActive(p),
    }));
  res.json(polls);
});

// Создание опроса (polls_save.php)
router.post('/', requireUser, (req, res) => {
  ensureDirs();
  const { title: rawTitle, end_date, voting_type } = req.body || {};
  const title = String(rawTitle ?? '').trim().slice(0, 190);
  if (!title || !end_date) return res.status(400).json({ error: 'Заполните обязательные поля' });
  if (!isValidDate(end_date)) return res.status(400).json({ error: 'Некорректная дата окончания' });

  const questions = parseQuestions(req.body.questions);
  if (!questions.length) {
    return res.status(400).json({ error: 'Добавьте хотя бы один вопрос с ответами' });
  }

  let id = Math.floor(Date.now() / 1000);
  while (fs.existsSync(pollFile(id))) id++;

  const now = new Date();
  const pad = (n) => String(n).padStart(2, '0');
  const start_date = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;

  const poll = {
    id,
    title,
    title_short: getTechnicalTitle(title),
    start_date,
    end_date,
    creator: req.pollUser,
    voting_type: voting_type === 'anonymous' ? 'anonymous' : 'public',
    questions,
  };
  writeJson(pollFile(id), poll);

  const index = readJson(path.join(POLLS_DIR, 'index.json'), []);
  index.push({ id, title });
  writeJson(path.join(POLLS_DIR, 'index.json'), index);

  res.status(201).json({ success: true, id });
});

// Данные опроса для страницы голосования
router.get('/:id', (req, res) => {
  const user = getUser(req);
  const { poll } = req;
  const myVote = readVotes(req.pollId).find((v) => v.user === user);
  res.json({
    id: poll.id,
    title: poll.title,
    start_date: poll.start_date,
    end_date: poll.end_date,
    voting_type: poll.voting_type || 'public',
    active: isActive(poll),
    questions: poll.questions.map((q) => ({ id: q.id, text: q.text, type: q.type, answers: q.answers })),
    voted: !!myVote,
    myAnswers: myVote ? myVote.answer : [],
    permissions: {
      results: !!user && canViewResults(poll, user),
      edit: !!user && canEdit(poll, user),
    },
  });
});

// Данные опроса для формы редактирования (polls_change_form.php)
router.get('/:id/edit', requireUser, (req, res) => {
  const { poll } = req;
  if (!canEdit(poll, req.pollUser)) {
    return res.status(403).json({ error: 'У вас нет прав на редактирование этого опроса' });
  }
  res.json({
    id: poll.id,
    title: poll.title,
    start_date: poll.start_date,
    end_date: poll.end_date,
    voting_type: poll.voting_type || 'public',
    questions: poll.questions.map((q, i) => ({
      origIndex: i, text: q.text, type: q.type, answers: q.answers,
    })),
  });
});

// Изменение опроса (polls_change.php)
router.put('/:id', requireUser, (req, res) => {
  const { poll, pollUser: user, pollId: id } = req;
  if (!canEdit(poll, user)) return res.status(403).json({ error: 'У вас нет прав на редактирование' });

  const { title: rawTitle, start_date, end_date, voting_type } = req.body || {};
  const title = String(rawTitle ?? '').trim().slice(0, 190);
  if (!title || !start_date || !end_date) {
    return res.status(400).json({ error: 'Заполните обязательные поля' });
  }
  if (!isValidDate(start_date) || !isValidDate(end_date)) {
    return res.status(400).json({ error: 'Некорректная дата' });
  }
  if (new Date(end_date) < new Date()) {
    return res.status(400).json({
      error: 'Дата окончания опроса уже прошла. Нельзя сохранить с прошедшей датой.',
    });
  }

  const questions = parseQuestions(req.body.questions, poll);
  if (!questions.length) {
    return res.status(400).json({ error: 'Добавьте хотя бы один вопрос с ответами' });
  }

  Object.assign(poll, {
    title,
    title_short: getTechnicalTitle(title),
    start_date,
    end_date,
    voting_type: voting_type === 'anonymous' ? 'anonymous' : 'public',
    questions,
  });
  writeJson(pollFile(id), poll);
  appendLog(
    'changed_log.txt',
    `ID=${id} | Title="${title}" | Updated at=${stamp()} | By=${user}`,
  );
  res.json({ success: true });
});

// Удаление опроса (polls_delete.php) — файлы переносятся в deleted_polls
router.delete('/:id', requireUser, (req, res) => {
  const { poll, pollUser: user, pollId: id } = req;
  if (!canEdit(poll, user)) return res.status(403).json({ error: 'У вас нет прав на удаление' });

  fs.mkdirSync(DELETED_DIR, { recursive: true });
  fs.renameSync(pollFile(id), path.join(DELETED_DIR, `${id}.json`));
  appendLog(
    'deleted_log.txt',
    `ID=${id} | Title="${poll.title}" | Deleted at=${stamp()} | By=${user}`,
  );
  res.json({ success: true });
});

// Голосование (polls_vote.php)
router.post('/:id/vote', requireUser, (req, res) => {
  const { poll, pollUser: user, pollId: id } = req;
  if (!isActive(poll)) return res.status(400).json({ error: 'Срок голосования истёк' });

  ensureDirs();
  const votes = readVotes(id);
  if (votes.some((v) => v.user === user)) return res.status(400).json({ error: 'Вы уже голосовали' });

  const submitted = req.body?.answers || {};
  const keys = [];
  for (let qi = 0; qi < poll.questions.length; qi++) {
    const q = poll.questions[qi];
    const raw = submitted[qi];
    const picked = (Array.isArray(raw) ? raw : raw === undefined || raw === null ? [] : [raw]).map(Number);
    const unique = [...new Set(picked)];
    if (!unique.length) {
      return res.status(400).json({ error: 'Пожалуйста, ответьте на все вопросы, выбрав хотя бы один вариант в каждом.' });
    }
    if (unique.some((ai) => !Number.isInteger(ai) || ai < 0 || ai >= q.answers.length)
      || (q.type === 'single' && unique.length > 1)) {
      return res.status(400).json({ error: 'Некорректные данные голосования' });
    }
    unique.forEach((ai) => {
      q.votes[ai]++;
      keys.push(`${q.id}_${ai}`);
    });
  }

  writeJson(pollFile(id), poll);
  votes.push({ user, answer: keys });
  writeJson(votesFile(id), votes);
  res.json({ success: true });
});

// Отмена голоса (polls_unvote.php)
router.delete('/:id/vote', requireUser, (req, res) => {
  const { poll, pollUser: user, pollId: id } = req;
  if (!isActive(poll)) {
    return res.status(400).json({ error: 'Нельзя отменить голос, так как опрос уже завершён.' });
  }

  const votes = readVotes(id);
  const idx = votes.findIndex((v) => v.user === user);
  if (idx === -1) return res.status(400).json({ error: 'Вы не голосовали' });

  const [removed] = votes.splice(idx, 1);
  writeJson(votesFile(id), votes);

  (removed.answer || []).forEach((key) => {
    const [qId, ai] = String(key).split('_');
    const q = poll.questions.find((x) => x.id === qId);
    if (q && q.votes[Number(ai)] !== undefined) q.votes[Number(ai)] = Math.max(0, q.votes[Number(ai)] - 1);
  });
  writeJson(pollFile(id), poll);
  res.json({ success: true });
});

function requireResultsAccess(req, res, next) {
  if (!canViewResults(req.poll, req.pollUser)) {
    return res.status(403).json({ error: 'У вас нет прав на просмотр результатов' });
  }
  next();
}

// Результаты (polls_results.php)
router.get('/:id/results', requireUser, requireResultsAccess, (req, res) => {
  const { poll, pollId: id } = req;
  const votingType = poll.voting_type || 'public';
  const votes = readVotes(id);

  res.json({
    id: poll.id,
    title: poll.title,
    voting_type: votingType,
    totalVoters: votes.length,
    questions: poll.questions.map((q) => {
      const total = q.votes.reduce((s, n) => s + n, 0);
      return {
        id: q.id,
        text: q.text,
        answers: q.answers.map((text, i) => {
          const count = q.votes[i];
          const key = `${q.id}_${i}`;
          const voters = votingType === 'public' && count > 0
            ? votes.filter((v) => (v.answer || []).includes(key)).map((v) => displayName(v.user))
            : [];
          return { text, count, percent: total > 0 ? Math.round((count / total) * 100) : 0, voters };
        }),
      };
    }),
  });
});

// Выгрузка в CSV (polls_export_csv.php)
const csvCell = (v) => {
  const s = String(v ?? '');
  return /[;"\r\n]/.test(s) ? `"${s.replace(/"/g, '""')}"` : s;
};

router.get('/:id/export.csv', requireUser, requireResultsAccess, (req, res) => {
  const { poll, pollId: id } = req;
  const isPublic = (poll.voting_type || 'public') === 'public';
  const votes = readVotes(id);
  const rows = [[poll.title, 'Голоса (кол-во)', 'ФИО'], []];

  poll.questions.forEach((q) => {
    rows.push([q.text]);
    q.answers.forEach((answer, i) => {
      const key = `${q.id}_${i}`;
      const chosen = votes.filter((v) => (v.answer || []).includes(key));
      rows.push(isPublic
        ? [answer, chosen.length, chosen.map((v) => displayName(v.user)).join('\n')]
        : [answer, chosen.length]);
    });
    rows.push([]);
  });

  const body = rows.map((r) => r.map(csvCell).join(';')).join('\r\n')
    + `\r\n\r\nПроголосовало: ${votes.length} человек`;

  const d = new Date();
  const p2 = (n) => String(n).padStart(2, '0');
  const filename = `${translit(poll.title_short || poll.title)}_${d.getFullYear()}${p2(d.getMonth() + 1)}${p2(d.getDate())}_${p2(d.getHours())}${p2(d.getMinutes())}.csv`;

  res.set({
    'Content-Type': 'text/csv; charset=utf-8',
    'Content-Disposition': `attachment; filename="${filename}"`,
    'Cache-Control': 'no-store',
  });
  // BOM — чтобы Excel открыл кириллицу в UTF-8 корректно
  res.send(String.fromCharCode(0xFEFF) + body);
});

function stamp() {
  const d = new Date();
  const p2 = (n) => String(n).padStart(2, '0');
  return `${d.getFullYear()}-${p2(d.getMonth() + 1)}-${p2(d.getDate())} ${p2(d.getHours())}:${p2(d.getMinutes())}:${p2(d.getSeconds())}`;
}

module.exports = router;
