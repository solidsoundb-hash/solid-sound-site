// Short run of vp.js: only /es/ pages and the English Sell page (25.11, «Копирайтер»).
process.env.VP_ONLY = '^/es/|^/en/sell/';
require('./vp.js');
