<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use App\Models\EventRegistration;
use App\Models\MembershipApplication;
use App\Support\SpamDetector;
use Illuminate\Console\Command;

/**
 * Проходит по всем заявкам, помечает подозрительные флагом is_spam=true.
 * Админу эти записи больше не показываются (default-фильтр), но они остаются в БД.
 *
 * Использование:
 *   php artisan meyos:spam-cleanup           # прогон: пометит is_spam=true
 *   php artisan meyos:spam-cleanup --delete  # УДАЛИТЬ помеченный как спам
 *   php artisan meyos:spam-cleanup --dry-run # посчитать, не менять
 */
class SpamCleanupCommand extends Command
{
    protected $signature = 'meyos:spam-cleanup
        {--dry-run : Только посчитать подозрительные, ничего не менять}
        {--delete  : Удалить уже помеченные (is_spam=true) записи навсегда}';

    protected $description = 'Помечает existing заявки как спам по эвристикам SpamDetector';

    public function handle(): int
    {
        $dry    = (bool) $this->option('dry-run');
        $delete = (bool) $this->option('delete');

        if ($delete) {
            return $this->deleteFlagged();
        }

        $classes = [
            MembershipApplication::class,
            ContactMessage::class,
            EventRegistration::class,
        ];

        $totalMarked = 0;
        foreach ($classes as $class) {
            $marked = 0;
            foreach ($class::query()->where('is_spam', false)->get() as $row) {
                $data = [
                    'name'    => $row->name,
                    'company' => $row->company,
                    'email'   => $row->email,
                    'phone'   => $row->phone,
                    'message' => $row->message ?? '',
                ];
                if (SpamDetector::looksLikeSpam($data)) {
                    if (!$dry) {
                        $row->is_spam = true;
                        $row->saveQuietly(); // не триггерим TelegramNotifier
                    }
                    $marked++;
                }
            }
            $this->line(sprintf('  %s: помечено %d', class_basename($class), $marked));
            $totalMarked += $marked;
        }

        $this->info($dry
            ? "Dry-run: нашлось бы $totalMarked спам-записей (не изменено)."
            : "Готово: помечено is_spam=true для $totalMarked записей. Они больше не видны в админке.");

        if (!$dry && $totalMarked > 0) {
            $this->line('Чтобы удалить навсегда: php artisan meyos:spam-cleanup --delete');
        }

        return self::SUCCESS;
    }

    private function deleteFlagged(): int
    {
        $counts = [
            'MembershipApplication' => MembershipApplication::where('is_spam', true)->forceDelete(),
            'ContactMessage'        => ContactMessage::where('is_spam', true)->forceDelete(),
            'EventRegistration'     => EventRegistration::where('is_spam', true)->forceDelete(),
        ];
        foreach ($counts as $name => $n) {
            $this->line("  $name: удалено $n");
        }
        $this->info('Готово. Удалены все записи с is_spam=true.');
        return self::SUCCESS;
    }
}
