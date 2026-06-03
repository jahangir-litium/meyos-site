<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Основное')->schema([
                
                TextInput::make('slug')->required()->unique(\App\Models\Page::class, 'slug', ignoreRecord: true)->disabled(),
                TextInput::make('view')->label('Blade view')->disabled(),
                Toggle::make('is_published')->default(true),
        
            ])->columns(2),

            Section::make('Содержание (RU / UZ / EN)')
                ->schema([
                    TranslatableTabs::make(array (
  'title' => 
  array (
    'label' => 'Название страницы',
    'type' => 'text',
    'required' => true,
  ),
  'seo_title' => 
  array (
    'label' => 'SEO title',
    'type' => 'text',
  ),
  'seo_description' => 
  array (
    'label' => 'SEO description',
    'type' => 'textarea',
  ),
  'seo_keywords' => 
  array (
    'label' => 'SEO keywords',
    'type' => 'text',
  ),
  'hero_tag' => 
  array (
    'label' => 'Hero — тег',
    'type' => 'text',
  ),
  'hero_h1' => 
  array (
    'label' => 'Hero — заголовок H1',
    'type' => 'textarea',
  ),
  'hero_lead' => 
  array (
    'label' => 'Hero — подзаголовок',
    'type' => 'textarea',
  ),
)),
                ]),
        ]);
    }
}
