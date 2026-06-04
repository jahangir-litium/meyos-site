# Если «Загрузка файла…» висит навсегда

## ✅ Что я уже сделал на ВАШЕЙ машине

1. ✅ Создал папку `C:\php-tmp` с правами на запись
2. ✅ Отредактировал **системный php.ini** (`C:\php-8.3.11-src\php.ini`):
   ```ini
   upload_tmp_dir = "C:\\php-tmp"
   ```
   ⚠️ Двойные backslash и кавычки обязательны.
3. ✅ Все Laravel/Livewire/Filament-уровневые фиксы — в коде

## 👉 Что нужно сделать ВАМ

### Шаг 1: Закройте текущий `php artisan serve`

В терминале, где запущен сервер — нажмите `Ctrl+C`.

### Шаг 2: Запустите заново

```cmd
cd "E:\Проекты\Coming soon\Клиенты\МИЕЗ\MEYOS site\meyos-laravel"
php artisan serve --host=127.0.0.1 --port=8000
```

### Шаг 3: Откройте админку и попробуйте upload

```
http://127.0.0.1:8000/admin/news/1/edit
```

Залейте картинку через FilePond → должна появиться нормально с превью.

### Шаг 4: Если ВСЁ ЕЩЁ висит

Проверьте что PHP подхватил upload_tmp_dir:
```cmd
php -r "echo ini_get('upload_tmp_dir');"
```
Должно показать `C:\php-tmp`. Если пусто:
- Запустите `php --ini` и посмотрите путь к **Loaded Configuration File**
- Откройте его и убедитесь что есть строка `upload_tmp_dir = "C:\\php-tmp"`
- Возможно у вас другой php.ini загружен

## 🐛 Корень бага (для понимания)

PHP на Windows **не может сохранять multipart upload buffer** когда системный
TMP-путь содержит non-ASCII символы (кириллица, спецсимволы).

Путь вашего проекта: `E:\Проекты\Coming soon\Клиенты\МИЕЗ\MEYOS site\...`
содержит кириллицу. Это значит:
- `php artisan serve` → запускает PHP cli-server из этой папки
- При upload PHP пытается записать в TMP который по дефолту в `%TEMP%`
- Если %TEMP% или путь обработки имеет non-ASCII → fail на Windows API
- Симптом в логах PHP: `Warning: File upload error - unable to create a temporary file`
- FilePond ждёт preview ответа который никогда не приходит → «Загрузка файла…»

**Решение:** заставить PHP использовать `C:\php-tmp` (ASCII-only) для upload buffer
через `upload_tmp_dir` в php.ini. Это и сделано.

## 🚀 Альтернатива: переместить проект

Если не хотите ковыряться с php.ini — просто переместите проект в путь
без кириллицы:
```cmd
xcopy /E /I "E:\Проекты\Coming soon\Клиенты\МИЕЗ\MEYOS site\meyos-laravel" "C:\meyos"
cd C:\meyos
php artisan serve
```
Работает 100% без правки php.ini.

## 🌍 На проде (Linux/macOS)

Проблема **НЕ ВОЗНИКАЕТ**. Linux/macOS нормально обрабатывают любые пути.
На сервере просто `composer install && php artisan serve` — всё работает.

## 📦 Что уже сделано в коде проекта

Эти фиксы остаются на месте и работают везде:

- `config/livewire.php` — явный disk + directory для temporary uploads
- `config/filesystems.php` — `/storage` относительный URL (фикс host mismatch)
- `app/Providers/AppServiceProvider` — `URL::forceRootUrl(request host)`
  (Livewire preview-URL автоматически использует тот же origin что страница)
- `app/Filament/Support/ImageUpload` — без `imageEditor()` и `imageResizeMode()`
  (они ломают submit в Filament 5 + Livewire 4)
- `storage/app/private/livewire-tmp` — папка создана с правами 0775

## ✅ Проверка

В DevTools → Network → залейте картинку через FilePond. Запрос
`POST /livewire-{hash}/upload-file` должен вернуть:
- ✅ `200 OK` с JSON — upload прошёл, превью появится
- ❌ `401 Unauthorized` с body `Warning: File upload error - unable to create a temporary file`
  — PHP всё ещё не может писать в TMP. Перезапустите `php artisan serve` после
  правки php.ini, либо переместите проект в ASCII-path.
