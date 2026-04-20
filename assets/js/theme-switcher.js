/* ==========================================================================
   MEYOS — Theme Switcher v3
   - COLOR:   green / wood / sapphire
   - CONCEPT: flow / corporate / forge / sovereign
   Каждая концепция знает, какие цвета с ней совместимы. Несовместимые
   цветовые кружки скрываются автоматически.
   ========================================================================== */

const COLORS = [
  { id: 'green',    name: 'Лес',    sub: 'Green'  },
  { id: 'wood',     name: 'Дерево', sub: 'Wood'   },
  { id: 'sapphire', name: 'Сапфир', sub: 'Blue'   }
];

const CONCEPTS = [
  {
    id: 'flow', name: 'Экосистема', sub: 'Минимализм',
    allowedColors: ['green', 'wood', 'sapphire']
  },
  {
    id: 'corporate', name: 'Корпоративный', sub: 'Classic',
    allowedColors: ['green', 'sapphire']
  },
  {
    id: 'forge', name: 'Кузница', sub: 'Dark Industrial',
    allowedColors: ['sapphire', 'wood']
  },
  {
    id: 'sovereign', name: 'Суверен', sub: 'Institutional',
    allowedColors: ['wood', 'sapphire']
  }
];

/* Hero-копи и картинки под каждую концепцию, в 3 языках */
const CONCEPT_HERO = {
  flow: {
    tag: { ru: 'Цифровая экосистема', uz: 'Raqamli ekotizim', en: 'Digital Ecosystem' },
    h1:  { ru: 'MEYOS — драйвер роста мебельной отрасли Узбекистана',
           uz: 'MEYOS — Oʻzbekiston mebel sohasining oʻsish drayveri',
           en: 'MEYOS — the growth driver of Uzbekistan\'s furniture industry' },
    lead: { ru: 'Объединяем производителей, дизайнеров и поставщиков в единую цифровую экосистему. Льготы, партнёрства, экспорт и цифровые инструменты — для роста вашего бизнеса.',
            uz: 'Ishlab chiqaruvchilar, dizaynerlar va taʼminotchilarni yagona raqamli ekotizimga birlashtiramiz.',
            en: 'We unite manufacturers, designers, and suppliers into a single digital ecosystem.' },
    img: 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=900&auto=format&fit=crop&q=80',
    floaterIcon: 'trending_up',
    floaterTitle: { ru: 'Цифровой рост', uz: 'Raqamli oʻsish', en: 'Digital Growth' },
    floaterText:  { ru: 'Члены ассоциации растут в среднем на 38% в год благодаря экосистеме партнёрств.',
                    uz: 'Assotsiatsiya aʼzolari yiliga oʻrtacha 38% oʻsadi.',
                    en: 'Members grow by an average of 38% per year.' }
  },
  corporate: {
    tag: { ru: 'IT и стратегический партнёр', uz: 'IT va strategik hamkor', en: 'IT & Strategic Partner' },
    h1:  { ru: 'MEYOS — стратегический партнёр мебельного бизнеса',
           uz: 'MEYOS — mebel biznesining strategik hamkori',
           en: 'MEYOS — a strategic partner for furniture business' },
    lead: { ru: 'Мы структурируем рост отрасли. Льготы, аналитика рынка, защита интересов перед регулятором и доступ к закрытой B2B-сети — в одном корпоративном решении.',
            uz: 'Soha oʻsishini tizimlashtiramiz. Imtiyozlar, bozor tahlili va yopiq B2B tarmoq — yagona korporativ yechimda.',
            en: 'We structure industry growth. Benefits, market analytics, regulatory voice, and a closed B2B network — in one corporate solution.' },
    img: 'https://images.unsplash.com/photo-1556742044-3c52d6e88c62?w=900&auto=format&fit=crop&q=80',
    floaterIcon: 'insights',
    floaterTitle: { ru: 'Рост выручки', uz: 'Daromad oʻsishi', en: 'Revenue Growth' },
    floaterText:  { ru: '+38% за год — средний показатель по резидентам ассоциации.',
                    uz: 'Yiliga +38% — assotsiatsiya rezidentlari boʻyicha oʻrtacha koʻrsatkich.',
                    en: '+38% per year — average across association residents.' }
  },
  forge: {
    tag: { ru: 'Суверенная кузница технологий', uz: 'Suveren texnologiyalar zarbxonasi', en: 'Sovereign Forge of Tech' },
    h1:  { ru: 'MEYOS. Инженерный суверенитет мебельной индустрии',
           uz: 'MEYOS. Mebel sohasining muhandislik suvereniteti',
           en: 'MEYOS. Engineering sovereignty of the furniture industry' },
    lead: { ru: 'Платформа индустриального роста: от цифровых двойников производства до коллективного экспорта. Мы превращаем мебельных производителей в технологических лидеров региона.',
            uz: 'Sanoat oʻsish platformasi: ishlab chiqarishning raqamli egizaklari va jamoaviy eksport.',
            en: 'Industrial growth platform: from digital twins of production to collective export.' },
    img: 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=900&auto=format&fit=crop&q=80',
    floaterIcon: 'precision_manufacturing',
    floaterTitle: { ru: 'Промышленная мощь', uz: 'Sanoat qudrati', en: 'Industrial Power' },
    floaterText:  { ru: 'Единая производственная сеть резидентов: 500+ фабрик, 12 стран экспорта, 22% доли рынка.',
                    uz: 'Rezidentlarning yagona ishlab chiqarish tarmogʻi: 500+ fabrika, 12 mamlakat.',
                    en: 'Single production network of residents: 500+ factories, 12 export countries.' }
  },
  sovereign: {
    tag: { ru: 'Национальная инициатива', uz: 'Milliy tashabbus', en: 'National Initiative' },
    h1:  { ru: 'Мебельная ассоциация MEYOS. Институциональная сила отрасли',
           uz: 'MEYOS mebel assotsiatsiyasi. Sohaning institutsional kuchi',
           en: 'MEYOS Furniture Association. Institutional strength of the industry' },
    lead: { ru: 'Государственно-ориентированная структура, которая формирует стандарты, защищает интересы индустрии и реализует национальные программы развития мебельного рынка.',
            uz: 'Davlatga yoʻnaltirilgan tuzilma, sohaning manfaatlarini himoya qiladi va milliy dasturlarni amalga oshiradi.',
            en: 'A state-oriented structure shaping standards, protecting industry interests, and running national programs.' },
    img: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=900&auto=format&fit=crop&q=80',
    floaterIcon: 'account_balance',
    floaterTitle: { ru: 'Мандат отрасли', uz: 'Soha mandati', en: 'Industry Mandate' },
    floaterText:  { ru: 'Единственная ассоциация с правом диалога с министерствами и экспертизы отраслевых законов.',
                    uz: 'Vazirliklar bilan muloqot huquqiga ega yagona assotsiatsiya.',
                    en: 'The only association with mandate for dialogue with ministries.' }
  }
};

const DEFAULT_COLOR   = 'green';
const DEFAULT_CONCEPT = 'flow';
const STORAGE_COLOR   = 'meyos-color';
const STORAGE_CONCEPT = 'meyos-concept';

function currentLang() {
  try { return localStorage.getItem('meyos-lang') || document.documentElement.lang || 'ru'; }
  catch (_) { return 'ru'; }
}

function findConcept(id) { return CONCEPTS.find(c => c.id === id) || CONCEPTS[0]; }

function applyColor(id, opts = {}) {
  const concept = findConcept(document.documentElement.getAttribute('data-concept') || DEFAULT_CONCEPT);
  const finalId = concept.allowedColors.includes(id) ? id : concept.allowedColors[0];
  document.documentElement.setAttribute('data-color', finalId);
  if (!opts.silent) {
    try { localStorage.setItem(STORAGE_COLOR, finalId); } catch (_) {}
    flash();
  }
  updateSwitcher();
}

function applyConcept(id, opts = {}) {
  const concept = findConcept(id);
  document.documentElement.setAttribute('data-concept', concept.id);
  /* Если текущий цвет не разрешён — переключить на первый доступный */
  const curColor = document.documentElement.getAttribute('data-color') || DEFAULT_COLOR;
  if (!concept.allowedColors.includes(curColor)) {
    document.documentElement.setAttribute('data-color', concept.allowedColors[0]);
    try { localStorage.setItem(STORAGE_COLOR, concept.allowedColors[0]); } catch (_) {}
  }
  if (!opts.silent) {
    try { localStorage.setItem(STORAGE_CONCEPT, concept.id); } catch (_) {}
    flash();
  }
  updateHero(concept.id);
  updateSwitcher();
}

function flash() {
  document.body.classList.remove('theme-flash');
  void document.body.offsetWidth;
  document.body.classList.add('theme-flash');
  setTimeout(() => document.body.classList.remove('theme-flash'), 800);
}

function updateHero(conceptId) {
  const root = document.querySelector('[data-hero-root]');
  if (!root) return;
  const h = CONCEPT_HERO[conceptId];
  if (!h) return;
  const lang = currentLang();
  const t = (obj) => (typeof obj === 'string' ? obj : (obj[lang] || obj.ru));
  setText(root, '[data-hero-tag]', t(h.tag));
  setText(root, '[data-hero-h1]',  t(h.h1));
  setText(root, '[data-hero-lead]', t(h.lead));
  setText(root, '[data-hero-floater-title]', t(h.floaterTitle));
  setText(root, '[data-hero-floater-text]',  t(h.floaterText));
  const icon = root.querySelector('[data-hero-floater-icon]');
  if (icon) icon.textContent = h.floaterIcon;
  const img = root.querySelector('[data-hero-img]');
  if (img) img.src = h.img;
}

function setText(root, sel, text) {
  const el = root.querySelector(sel);
  if (el) el.textContent = text;
}

function updateSwitcher() {
  const sw = document.querySelector('.theme-switcher');
  if (!sw) return;
  const color   = document.documentElement.getAttribute('data-color')   || DEFAULT_COLOR;
  const concept = findConcept(document.documentElement.getAttribute('data-concept') || DEFAULT_CONCEPT);
  sw.querySelectorAll('[data-color-option]').forEach(b => {
    const on = concept.allowedColors.includes(b.dataset.colorOption);
    b.classList.toggle('is-hidden', !on);
    b.style.display = on ? '' : 'none';
    b.classList.toggle('is-active', b.dataset.colorOption === color);
  });
  sw.querySelectorAll('[data-concept-option]').forEach(b => {
    b.classList.toggle('is-active', b.dataset.conceptOption === concept.id);
  });
}

function renderSwitcher() {
  if (document.querySelector('.theme-switcher')) return;
  const sw = document.createElement('div');
  sw.className = 'theme-switcher';
  sw.setAttribute('role', 'toolbar');
  sw.innerHTML = `
    <div class="theme-switcher__group">
      <span class="theme-switcher__label" data-i18n="switcher.color">Цвет</span>
      ${COLORS.map(c => `<button class="theme-color" data-color-option="${c.id}" aria-label="${c.name}" title="${c.name}"></button>`).join('')}
    </div>
    <div class="theme-switcher__group">
      <span class="theme-switcher__label" data-i18n="switcher.concept">Концепция</span>
      ${CONCEPTS.map(c => `<button class="theme-concept" data-concept-option="${c.id}" title="${c.sub}" data-i18n="concept.${c.id}">${c.name}</button>`).join('')}
    </div>
  `;
  document.body.appendChild(sw);

  sw.querySelectorAll('[data-color-option]').forEach(btn => {
    btn.addEventListener('click', () => applyColor(btn.dataset.colorOption));
  });
  sw.querySelectorAll('[data-concept-option]').forEach(btn => {
    btn.addEventListener('click', () => applyConcept(btn.dataset.conceptOption));
  });
}

function init() {
  let c = null, k = null;
  try { c = localStorage.getItem(STORAGE_COLOR); k = localStorage.getItem(STORAGE_CONCEPT); } catch (_) {}
  const conceptId = CONCEPTS.some(x => x.id === k) ? k : DEFAULT_CONCEPT;
  const concept = findConcept(conceptId);
  const colorId = concept.allowedColors.includes(c) ? c : concept.allowedColors[0];
  renderSwitcher();
  applyConcept(conceptId, { silent: true });
  applyColor(colorId, { silent: true });

  window.addEventListener('meyos:lang', () => {
    const cur = document.documentElement.getAttribute('data-concept') || DEFAULT_CONCEPT;
    updateHero(cur);
  });
}

window.MEYOS_THEME = { applyColor, applyConcept };

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
