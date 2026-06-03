<?php

namespace App\Filament\Support;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Утилита для построения табов RU/UZ/EN для переводимых полей.
 *
 * Пример:
 *   TranslatableTabs::make([
 *     'title'   => ['label' => 'Заголовок', 'type' => 'text', 'required' => true],
 *     'preview' => ['label' => 'Превью',    'type' => 'textarea'],
 *     'content' => ['label' => 'Полный текст', 'type' => 'rich'],
 *   ])
 */
class TranslatableTabs
{
    public const LANGUAGES = ['ru' => 'Русский', 'uz' => 'Oʻzbekcha', 'en' => 'English'];

    public static function make(array $fields): Tabs
    {
        $tabs = [];
        foreach (self::LANGUAGES as $code => $label) {
            $components = [];
            foreach ($fields as $name => $opts) {
                $components[] = static::buildField($name, $code, $opts);
            }
            $tabs[] = Tab::make($label)->schema($components);
        }
        return Tabs::make()->tabs($tabs)->columnSpanFull();
    }

    protected static function buildField(string $name, string $lang, array $opts)
    {
        $type     = $opts['type']     ?? 'text';
        $label    = $opts['label']    ?? ucfirst($name);
        $required = $opts['required'] ?? false;
        $rows     = $opts['rows']     ?? 3;
        $key      = "$name.$lang";

        $field = match ($type) {
            'textarea' => Textarea::make($key)->rows($rows),
            'rich'     => RichEditor::make($key),
            default    => TextInput::make($key),
        };

        $field = $field->label($label);

        // required только для русского — остальные опционально
        if ($required && $lang === 'ru') {
            $field = $field->required();
        }

        return $field->columnSpanFull();
    }
}
