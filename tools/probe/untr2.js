// Ступень 2 робота непереведённых строк: DE/FR/ES × 8 страниц пути к покупке и для авторов.
process.env.UNTR_LANGS = process.env.UNTR_LANGS || 'de,fr,es';
process.env.UNTR_PAGES = process.env.UNTR_PAGES || '/services-prices/,/product/pitch/,/free/,/sell/,/cart/,/terms/,/license/,/creator-terms/';
process.env.UNTR_OUT = process.env.PROBE_OUT || 'untr-out';
require('../copy/untranslated.js');
