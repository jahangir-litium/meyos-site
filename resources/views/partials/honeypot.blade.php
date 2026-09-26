{{--
  Антибот: скрытые поля.
  - website: honeypot, люди не видят, боты автозаполняют
  - form_ts: время загрузки формы (сервер отбрасывает если < 3с или > 6ч)
--}}
<div style="position:absolute; left:-9999px; top:-9999px; opacity:0; pointer-events:none; height:0; overflow:hidden;" aria-hidden="true">
  <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
</div>
<input type="hidden" name="form_ts" value="{{ time() }}">
