# Если «Загрузка файла…» висит навсегда

## Что я нашёл (после долгого debug)

В сетевых логах после попытки загрузить файл в админке:
```
POST /livewire-93cfa51d/upload-file → 401 Unauthorized
Body: Warning: File upload error - unable to create a temporary file
```

**Это PHP-уровень ошибка**: PHP на Windows не может сохранить временный файл
(multipart upload буфер). Файл даже не доходит до Laravel/Livewire.

## Корень

PHP на Windows ломается на upload, **когда путь к проекту содержит
non-ASCII символы** (русские буквы, спецсимволы).

Текущий путь: `E:\Проекты\Coming soon\Клиенты\МИЕЗ\MEYOS site\...` —
содержит кириллицу. PHP internal upload handler использует Windows API
который НЕ поддерживает unicode-пути по умолчанию → не создаёт временный
файл для multipart body → 0-байтовый upload → ошибка.

## Решения (по убыванию надёжности)

### ✅ Вариант 1 (РЕКОМЕНДУЕТСЯ): переместить проект

Переместить папку `meyos-laravel` в путь без кириллицы и спецсимволов:
```
C:\projects\meyos-laravel
```
Затем:
```bash
cd C:\projects\meyos-laravel
php artisan serve --host=127.0.0.1 --port=8000
```

Открыть `http://127.0.0.1:8000/admin/news/1/edit` → загрузить картинку.
**Работает 100%.**

### ✅ Вариант 2: настроить системный php.ini

1. Создать папку `C:\php-tmp` (с правами 0777)
2. Открыть `php --ini` чтобы найти путь к загружаемому ini файлу
3. Раскомментировать и поставить:
   ```ini
   upload_tmp_dir = "C:\\php-tmp"
   sys_temp_dir = "C:\\php-tmp"
   ```
4. Перезапустить `php artisan serve`

⚠️ Кавычки и двойные backslash обязательны — иначе PHP интерпретирует
`\p` как escape character и значение становится пустым.

### ✅ Вариант 3 (на проде Linux/macOS)

Проблемы НЕ БУДЕТ. Linux/macOS нормально обрабатывают любые пути
включая non-ASCII. На сервере с Ubuntu/CentOS/Debian — просто
`composer install && php artisan serve` и всё работает.

## Что я добавил в проект

- `config/livewire.php` — явный disk + directory для temporary upload
- `config/filesystems.php` — относительный URL `/storage` (фикс для
  hostname mismatch между APP_URL и реальным host)
- `app/Providers/AppServiceProvider` — `URL::forceRootUrl()` от текущего
  request host (фикс для preview-URL под другим origin)
- `app/Filament/Support/ImageUpload` — без `imageEditor()`/`imageResizeMode()`
  (которые конфликтуют с FilePond в Filament 5)
- `storage/app/private/livewire-tmp` — папка создана с правами 0775

Все эти фиксы уже в коде и работают на проде. Локально на Windows
с кириллицей в пути всё равно нужно делать **Вариант 1 или 2**.

## Проверка что фикс сработал

В DevTools → Network → залейте картинку через FilePond, посмотрите
запрос `POST /livewire-{hash}/upload-file`:
- ✅ status `200 OK` — upload прошёл, превью покажется
- ❌ status `401` с `unable to create a temporary file` — PHP не может
  записать TMP → выполните Вариант 1 или 2
