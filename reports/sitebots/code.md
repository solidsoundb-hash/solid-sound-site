## 🧹 Код: мёртвый код и ошибки

Проверено файлов: **91** (2180 КБ).

| Проверка | Найдено |
|---|---|
| 🔴 Ошибки синтаксиса | 0 |
| 🔴 Глобальное имя перекрыто (как баг с плеером 21.11) | 0 |
| 🟠 Модули, которые не подключены | 0 |
| 🟠 Файлы сборки, которые никто не грузит | 0 |
| 🟡 PHP-функции без вызовов | 3 |
| 🟡 JS-функции без вызовов | 0 |
| 🟡 Наши CSS-классы, которых нет в разметке | 37 |
| 🟡 setInterval (правило: только MutationObserver/rAF) | 17 |
| 🟡 Скрипты без defer | 2 |
| ⚪ console.log в коде | 1 |
| ⚪ Повторы кода (6+ одинаковых строк) | 26 |

### 🟡 PHP-функции, которые нигде не вызываются
- `plugin/solid-sound-custom/src/custom.php:6850` — ss_is_vendor_user()
- `plugin/solid-sound-custom/src/modules/account.php:18` — ss_acc_is_vendor_only()
- `plugin/solid-sound-custom/src/modules/packages.php:51` — ss_pk_html()

### 🟡 CSS-классы без разметки (кандидаты на удаление — проверить, не ставит ли их чужой код)
- `.ss-ap-format`
- `.ss-ap-mp3`
- `.ssh-lead`
- `.ssh-lt`
- `.ssh-ls`
- `.ssh-tag-presets`
- `.ssh-tag-music`
- `.ssh-svc`
- `.ssh-sn`
- `.ssh-sp`
- `.ssh-svc-all`
- `.ssh-col-free`
- `.ssh-col-svc`
- `.ssh-from`
- `.ssh-tier-best`
- `.ssh-tier-trending`
- `.ss-soc`
- `.ss-cart-n`
- `.ss-ap-version`
- `.ss-ap-daw`
- `.ss-ds-off`
- `.ss-ap-genre`
- `.ss-ff-x1`
- `.ss-ff-x2`
- `.ss-ap-mood`
- `.ss-ap-ext`
- `.ss-ap-dl`
- `.ss-ap-bpm`
- `.ss-ap-key`
- `.ss-pp-buy`
- `.ss-wm-send`
- `.ss-ap-send`
- `.ssw-sub`
- `.ssg-tri-btn`
- `.ssw-rate`
- `.ss-hide`
- `.ss-lead-msg`

### 🟡 setInterval
- `plugin/solid-sound-custom/src/account/dashboard.js:134` — `setInterval(function(){ var ta=D.querySelector('#ssb textarea, .ssb-in textarea'); n++; if`
- `plugin/solid-sound-custom/src/custom.php:2003` — `setInterval(ss,1500);apply();`
- `plugin/solid-sound-custom/src/custom.php:2630` — `setInterval(fix,600);})();</script>`
- `plugin/solid-sound-custom/src/custom.php:4737` — `setInterval(upd,600);document.addEventListener('ssp:state',upd);`
- `plugin/solid-sound-custom/src/custom.php:4962` — `setInterval(function(){if(!document.body.contains(root)||over||document.hidden||Date.now()`
- `plugin/solid-sound-custom/src/custom.php:5433` — `setInterval(lift,800);addEventListener('resize',lift);`
- `plugin/solid-sound-custom/src/custom.php:6182` — `setInterval(function(){if(wrapSSP()||++tries>40)clearInterval(iv)},500)}}`
- `plugin/solid-sound-custom/src/home/app.template.html:669` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);`
- `plugin/solid-sound-custom/src/home/app.template.html:670` — `setInterval(function () {`
- `plugin/solid-sound-custom/src/home/patches.js:69` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 60000);").join("this._cd`
- `plugin/solid-sound-custom/src/home/patches.js:69` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);")`
- `plugin/solid-sound-custom/src/home/patches.js:70` — `setInterval(function () { self.setState({ tick: Date.now() }); }, 1000);").join("this._cd `
- `plugin/solid-sound-custom/src/home/patches.js:70` — `setInterval(function () { var L = self.cd(), S = self.cd(true), n = document.querySelector`
- `plugin/solid-sound-custom/src/modules/new-slider.php:146` — `setInterval(function(){if(document.hidden)return;if(Date.now()<hold)return;if(R.querySelec`
- `plugin/solid-sound-custom/src/ss-global.js:341` — `setInterval(function(){ var t=p.querySelector('.p-t'); if(t) t.textContent=promoLeft(); },`
- `plugin/solid-sound-custom/src/ss-global.js:388` — `setInterval(playIcons,800);`
- `plugin/solid-sound-custom/src/ss-global.js:433` — `setInterval(paint,700); window.addEventListener('resize',function(){ paint(true); },{passi`

### 🟡 Скрипты без defer
- `plugin/solid-sound-custom/src/modules/prices.php:14`
- `plugin/solid-sound-custom/src/modules/prices.php:15`

### ⚪ Повторы кода
- `plugin/solid-sound-custom/src/audio/ss-audio.js:269` = `plugin/solid-sound-custom/src/custom.php:3277`
- `plugin/solid-sound-custom/src/modules/home-wow.php:239` = `plugin/solid-sound-custom/src/modules/home-wow.php:250`
- `plugin/solid-sound-custom/src/home/ai-search.js:2` = `plugin/solid-sound-custom/src/ss-global.js:391`
- `plugin/solid-sound-custom/src/home/ai-search.js:8` = `plugin/solid-sound-custom/src/ss-global.js:397`
- `plugin/solid-sound-custom/src/home/ai-search.js:18` = `plugin/solid-sound-custom/src/ss-global.js:406`
- `plugin/solid-sound-custom/src/home/ai-search.js:43` = `plugin/solid-sound-custom/src/ss-global.js:435`
- `plugin/solid-sound-custom/src/home/ai-search.js:49` = `plugin/solid-sound-custom/src/ss-global.js:441`
- `plugin/solid-sound-custom/src/home/ai-search.js:55` = `plugin/solid-sound-custom/src/ss-global.js:447`
- `plugin/solid-sound-custom/src/home/ai-search.js:61` = `plugin/solid-sound-custom/src/ss-global.js:453`
- `plugin/solid-sound-custom/src/home/ai-search.js:67` = `plugin/solid-sound-custom/src/ss-global.js:459`
- `plugin/solid-sound-custom/src/home/ai-search.js:73` = `plugin/solid-sound-custom/src/ss-global.js:465`
- `plugin/solid-sound-custom/src/home/ai-search.js:87` = `plugin/solid-sound-custom/src/ss-global.js:479`
- `plugin/solid-sound-custom/src/home/ai-search.js:96` = `plugin/solid-sound-custom/src/ss-global.js:488`
- `plugin/solid-sound-custom/src/home/ai-search.js:102` = `plugin/solid-sound-custom/src/ss-global.js:494`
- `plugin/solid-sound-custom/src/home/ai-search.js:108` = `plugin/solid-sound-custom/src/ss-global.js:500`
- … и ещё 11

### Самые большие файлы
| Файл | КБ | Строк |
|---|---|---|
| `plugin/solid-sound-custom/src/custom.php` | 644 | 6942 |
| `plugin/solid-sound-custom/src/home/app.template.html` | 130 | 1139 |
| `plugin/solid-sound-custom/src/modules/home-wow.php` | 103 | 807 |
| `plugin/solid-sound-custom/src/ss-global.js` | 70 | 627 |
| `plugin/solid-sound-custom/src/modules/services-prices-html.php` | 69 | 33 |
| `plugin/solid-sound-custom/src/modules/files-first.php` | 55 | 358 |
| `plugin/solid-sound-custom/src/theme/player.js` | 52 | 611 |
| `plugin/solid-sound-custom/src/home/home.js` | 51 | 563 |
