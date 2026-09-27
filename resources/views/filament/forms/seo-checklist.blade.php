@php
    // Атрибуты: имена полей формы для проверки (передаются как data-*)
    $titleField = $titleField ?? 'seo_title.ru';
    $descField  = $descField  ?? 'seo_description.ru';
    $fallbackTitleField = $fallbackTitleField ?? 'name.ru';
    $fallbackDescField  = $fallbackDescField  ?? 'description.ru';
@endphp

<div class="seo-checklist"
     data-title-field="{{ $titleField }}"
     data-desc-field="{{ $descField }}"
     data-fallback-title="{{ $fallbackTitleField }}"
     data-fallback-desc="{{ $fallbackDescField }}"
     style="padding:1rem 1.25rem; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px;">

  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem;">
    <strong style="font-size:.95rem;">🔍 SEO-проверка</strong>
    <div>
      <span class="seo-score" style="font-size:1.5rem; font-weight:800; color:#6b7280;">—</span>
      <span style="font-size:.75rem; color:#6b7280;"> / 100</span>
    </div>
  </div>

  <ul class="seo-checks" style="list-style:none; padding:0; margin:0; display:grid; gap:.35rem; font-size:.85rem;">
    <li data-check="title-length">
      <span class="check-icon" style="color:#6b7280;">◯</span>
      <span class="check-label">Title 50-60 символов</span>
      <span class="check-value" style="color:#6b7280; font-size:.75rem;"></span>
    </li>
    <li data-check="desc-length">
      <span class="check-icon" style="color:#6b7280;">◯</span>
      <span class="check-label">Description 140-160 символов</span>
      <span class="check-value" style="color:#6b7280; font-size:.75rem;"></span>
    </li>
    <li data-check="title-present">
      <span class="check-icon" style="color:#6b7280;">◯</span>
      <span class="check-label">Заголовок заполнен</span>
    </li>
    <li data-check="desc-present">
      <span class="check-icon" style="color:#6b7280;">◯</span>
      <span class="check-label">Описание заполнено</span>
    </li>
    <li data-check="title-unique">
      <span class="check-icon" style="color:#6b7280;">◯</span>
      <span class="check-label">Title отличается от заголовка</span>
      <span class="check-value" style="color:#6b7280; font-size:.75rem;">(SEO-title не должен дублировать h1)</span>
    </li>
  </ul>

  <p style="margin-top:.75rem; font-size:.75rem; color:#9ca3af;">
    Метрики обновляются в реальном времени. Оптимальный балл: 80+
  </p>
</div>

<style>
.seo-checklist li { display: grid; grid-template-columns: 1.5rem 1fr auto; align-items: center; gap: .5rem; }
.seo-checklist li.ok .check-icon { color: #10b981; }
.seo-checklist li.warn .check-icon { color: #f59e0b; }
.seo-checklist li.fail .check-icon { color: #ef4444; }
</style>

<script>
(function() {
  const containers = document.querySelectorAll('.seo-checklist:not([data-inited])');
  containers.forEach(container => {
    container.setAttribute('data-inited', '1');

    const titleField    = container.dataset.titleField;
    const descField     = container.dataset.descField;
    const fbTitleField  = container.dataset.fallbackTitle;
    const fbDescField   = container.dataset.fallbackDesc;

    const getFieldValue = (name) => {
      const el = document.querySelector(
        `input[wire\\:model="data.${name}"], textarea[wire\\:model="data.${name}"],`
        + `input[wire\\:model\\.live="data.${name}"], textarea[wire\\:model\\.live="data.${name}"],`
        + `input[wire\\:model\\.blur="data.${name}"], textarea[wire\\:model\\.blur="data.${name}"],`
        + `input[name*="${name}"], textarea[name*="${name}"]`
      );
      return el ? el.value : '';
    };

    const update = () => {
      const title = getFieldValue(titleField) || getFieldValue(fbTitleField);
      const desc  = getFieldValue(descField)  || getFieldValue(fbDescField);
      const fallbackTitle = getFieldValue(fbTitleField);

      let score = 0, maxScore = 5;

      // title length 50-60
      const tl = title.length;
      const tItem = container.querySelector('[data-check="title-length"]');
      const tValue = tItem.querySelector('.check-value');
      const tIcon = tItem.querySelector('.check-icon');
      tValue.textContent = tl + ' симв.';
      tItem.classList.remove('ok', 'warn', 'fail');
      if (tl >= 50 && tl <= 60) { tItem.classList.add('ok'); tIcon.textContent = '✓'; score++; }
      else if (tl >= 30 && tl <= 70) { tItem.classList.add('warn'); tIcon.textContent = '⚠'; score += 0.5; }
      else { tItem.classList.add('fail'); tIcon.textContent = '✕'; }

      // desc length 140-160
      const dl = desc.length;
      const dItem = container.querySelector('[data-check="desc-length"]');
      const dValue = dItem.querySelector('.check-value');
      const dIcon = dItem.querySelector('.check-icon');
      dValue.textContent = dl + ' симв.';
      dItem.classList.remove('ok', 'warn', 'fail');
      if (dl >= 140 && dl <= 160) { dItem.classList.add('ok'); dIcon.textContent = '✓'; score++; }
      else if (dl >= 100 && dl <= 200) { dItem.classList.add('warn'); dIcon.textContent = '⚠'; score += 0.5; }
      else { dItem.classList.add('fail'); dIcon.textContent = '✕'; }

      // title present
      const tPresent = container.querySelector('[data-check="title-present"]');
      tPresent.classList.remove('ok', 'fail');
      if (title.trim().length > 0) { tPresent.classList.add('ok'); tPresent.querySelector('.check-icon').textContent = '✓'; score++; }
      else { tPresent.classList.add('fail'); tPresent.querySelector('.check-icon').textContent = '✕'; }

      // desc present
      const dPresent = container.querySelector('[data-check="desc-present"]');
      dPresent.classList.remove('ok', 'fail');
      if (desc.trim().length > 0) { dPresent.classList.add('ok'); dPresent.querySelector('.check-icon').textContent = '✓'; score++; }
      else { dPresent.classList.add('fail'); dPresent.querySelector('.check-icon').textContent = '✕'; }

      // title unique from h1
      const tUnique = container.querySelector('[data-check="title-unique"]');
      tUnique.classList.remove('ok', 'warn', 'fail');
      if (title && fallbackTitle && title.trim() !== fallbackTitle.trim()) {
        tUnique.classList.add('ok'); tUnique.querySelector('.check-icon').textContent = '✓'; score++;
      } else if (!title) {
        tUnique.classList.add('warn'); tUnique.querySelector('.check-icon').textContent = '⚠';
      } else {
        tUnique.classList.add('warn'); tUnique.querySelector('.check-icon').textContent = '⚠';
      }

      // score bar
      const percentage = Math.round((score / maxScore) * 100);
      const scoreEl = container.querySelector('.seo-score');
      scoreEl.textContent = percentage;
      scoreEl.style.color = percentage >= 80 ? '#10b981' : (percentage >= 50 ? '#f59e0b' : '#ef4444');
    };

    // Инициальный расчёт
    setTimeout(update, 200);
    // Обновление при вводе в форму
    document.addEventListener('input', (e) => {
      if (e.target.matches('input, textarea')) setTimeout(update, 100);
    });
  });
})();
</script>
