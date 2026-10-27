// Очистка HTML-тегов из строки, оставляя только текст и переносы строк
module.exports = function cleanHtml(value) {
  if (value == null) return '';

  return String(value)
    // Заменяем теги абзацев и блочных элементов на переносы строк
    .replace(/<\/p\s*>/gi, '\n\n')
    .replace(/<p\b[^>]*>/gi, '')
    .replace(/<\/(div|section|article|h[1-6]|li|blockquote)\s*>/gi, '\n')
    .replace(/<(div|section|article|h[1-6]|li|blockquote)\b[^>]*>/gi, '')
    .replace(/<br\s*\/?>/gi, '\n')

    // Удаляем все оставшиеся HTML-теги
    .replace(/<[^>]*>/g, '')

    // Декодируем распространённые HTML-сущности
    .replace(/&nbsp;/gi, ' ')
    .replace(/&amp;/gi, '&')
    .replace(/&lt;/gi, '<')
    .replace(/&gt;/gi, '>')
    .replace(/&quot;/gi, '"')
    .replace(/&#39;|&apos;/gi, "'")

    // Убираем пробелы перед переносами и лишние пустые строки
    .replace(/[ \t]+\n/g, '\n')
    .replace(/\n[ \t]+/g, '\n')
    .replace(/\n{3,}/g, '\n\n')
    .trim();
};
