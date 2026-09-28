<x-filament-panels::page>
    <div class="fi-page-hint" style="padding:.85rem 1rem;background:rgb(var(--surface-deep,251 246 240));border:1px solid rgb(var(--outline,224 214 200));border-radius:.65rem;margin-bottom:1rem;font-size:.9rem;line-height:1.5;color:rgb(var(--on-surface-mut,113 96 76));">
        Здесь редактируются все короткие тексты сайта, которые не относятся к отдельным записям
        (новости, партнёры и т.д. — там свои разделы). Каждый текст сразу на 3 языках. Пустое поле
        для языка = fallback на русский вариант.
    </div>

    <form wire:submit="save">
        {{ $this->form }}
    </form>
</x-filament-panels::page>
