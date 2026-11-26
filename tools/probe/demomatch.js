// Probe (26.11, «Тестировщик»): demo ↔ product check (tools/sitebots/demo-match.js) once, report to shots-ab probe-demomatch/.
const fs = require('fs'); const OUT = process.env.PROBE_OUT || 'probe-demomatch'; fs.mkdirSync(OUT, { recursive: true });
require('../sitebots/demo-match')().then(r => { fs.writeFileSync(OUT + '/report.md', '# Демо ↔ товар — ' + new Date().toISOString() + '\n\n' + r.md + (r.alerts.length ? '\n' + r.alerts.map(a => '- ' + a).join('\n') + '\n' : '')); console.log(r.md); });
