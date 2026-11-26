// Ступень 1 робота непереведённых строк (docs/team/bots.md): одна страница.
process.env.UNTR_LANGS = 'de'; process.env.UNTR_PAGES = '/creator-terms/';
process.env.UNTR_OUT = process.env.PROBE_OUT || 'untr-out';
require('../copy/untranslated.js');
