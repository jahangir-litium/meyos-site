<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MembershipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SubmissionController extends Controller
{
    /**
     * Проверка на бота:
     *  1) Honeypot — поле `website` должно быть пустым (люди не видят, боты заполняют)
     *  2) Time-trap — форма отправляется не быстрее 3 сек после загрузки
     * При срабатывании — тихо редиректим с фейковым success (бот не должен понять что попался).
     */
    private function looksLikeBot(Request $request): bool
    {
        // 1) Honeypot
        if (filled($request->input('website'))) {
            return true;
        }
        // 2) Time-trap: скрытое поле form_ts несёт timestamp загрузки формы
        $ts = (int) $request->input('form_ts', 0);
        if ($ts > 0) {
            $ageSec = time() - $ts;
            // < 3 сек = бот, > 6 часов = устаревшая форма (тоже подозрительно)
            if ($ageSec < 3 || $ageSec > 21600) {
                return true;
            }
        }
        return false;
    }

    /** Тихий отказ — бот получает такой же успешный ответ как человек, но данные не сохраняем. */
    private function silentDrop(Request $request, string $successKey)
    {
        return $this->successResponse($request, $successKey);
    }

    /** Универсальный успешный ответ: JSON для AJAX, redirect для классического сабмита. */
    private function successResponse(Request $request, string $key)
    {
        $msg = $this->successMsg($key);
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'message' => $msg]);
        }
        return back()->with('success', $msg);
    }

    /** Универсальный ответ об ошибке. */
    private function errorResponse(Request $request)
    {
        $msg = $this->errorMsg();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => false, 'message' => $msg], 500);
        }
        return back()->withInput()->with('error', $msg);
    }

    /** Локализованное сообщение успеха для текущей локали. */
    private function successMsg(string $key): string
    {
        $messages = [
            'membership' => [
                'ru' => 'Заявка отправлена! Менеджер свяжется с вами в течение одного рабочего дня. Проверьте, пожалуйста, входящую почту — туда придёт подтверждение.',
                'uz' => 'Ariza yuborildi! Menejer bir ish kuni ichida bogʻlanadi. Iltimos, elektron pochtangizni tekshiring — tasdiq xati u yerga keladi.',
                'en' => 'Application sent! A manager will contact you within one business day. Please check your inbox for a confirmation.',
            ],
            'event' => [
                'ru' => 'Регистрация принята! Координатор свяжется для подтверждения участия. Информация о мероприятии — в письме.',
                'uz' => 'Roʻyxatga olindi! Koordinator ishtirokni tasdiqlash uchun bogʻlanadi. Tafsilotlar pochtangizda.',
                'en' => 'Registration received! A coordinator will reach out to confirm. Event details will arrive by email.',
            ],
            'contact' => [
                'ru' => 'Сообщение отправлено! Мы ответим в течение рабочего дня. Спасибо, что написали.',
                'uz' => 'Xabar yuborildi! Bir ish kuni ichida javob beramiz. Murojaatingiz uchun rahmat.',
                'en' => 'Message sent! We will reply within one business day. Thank you for reaching out.',
            ],
        ];
        $locale = app()->getLocale();
        return $messages[$key][$locale] ?? $messages[$key]['ru'];
    }

    /** Локализованное сообщение ошибки валидации/сохранения. */
    private function errorMsg(): string
    {
        $messages = [
            'ru' => 'Что-то пошло не так. Пожалуйста, проверьте поля и попробуйте ещё раз. Если ошибка повторится — напишите нам на email.',
            'uz' => 'Nimadir xato ketdi. Maydonlarni tekshiring va qayta urinib koʻring. Xato takrorlansa — pochta orqali yozing.',
            'en' => 'Something went wrong. Please check the fields and try again. If the error persists, email us.',
        ];
        return $messages[app()->getLocale()] ?? $messages['ru'];
    }

    public function membership(Request $request)
    {
        if ($this->looksLikeBot($request)) {
            return $this->silentDrop($request, 'membership');
        }

        $data = $request->validate([
            'company'  => 'required|string|max:255',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:50',
            'category' => 'nullable|string|max:255',
            'volume'   => 'nullable|string|max:255',
            'message'  => 'nullable|string|max:5000',
        ], $this->validationMessages());

        $data['source_page'] = substr((string) $request->headers->get('referer', ''), 0, 100);

        // UTM из сессии (запомнил TrackPageView)
        $utm = session('utm', []);
        $data['utm_source']   = $utm['utm_source']   ?? null;
        $data['utm_medium']   = $utm['utm_medium']   ?? null;
        $data['utm_campaign'] = $utm['utm_campaign'] ?? null;

        try {
            MembershipApplication::create($data);
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse($request);
        }

        return $this->successResponse($request, 'membership');
    }

    public function eventRegister(Request $request, ?string $slug = null)
    {
        if ($this->looksLikeBot($request)) {
            return $this->silentDrop($request, 'event');
        }

        $data = $request->validate([
            'event_id'        => 'nullable|integer|exists:events,id',
            'company'         => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:50',
            'attendees_count' => 'nullable|integer|min:1|max:20',
        ], $this->validationMessages());

        if ($slug && !isset($data['event_id'])) {
            $event = Event::where('slug', $slug)->first();
            if ($event) {
                $data['event_id'] = $event->id;
                $data['event_name'] = $event->getTranslation('title', 'ru', false);
            }
        }

        try {
            EventRegistration::create($data);
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse($request);
        }

        return $this->successResponse($request, 'event');
    }

    public function contact(Request $request)
    {
        if ($this->looksLikeBot($request)) {
            return $this->silentDrop($request, 'contact');
        }

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'topic'   => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ], $this->validationMessages());

        try {
            ContactMessage::create($data);
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse($request);
        }

        return $this->successResponse($request, 'contact');
    }

    /** Локализованные тексты валидационных ошибок (заменяют стандартный «field is required»). */
    private function validationMessages(): array
    {
        $locale = app()->getLocale();
        $messages = [
            'ru' => [
                'required' => 'Поле «:attribute» обязательно для заполнения',
                'email'    => 'Введите корректный email — например, name@company.uz',
                'max.string' => 'Поле «:attribute» слишком длинное (максимум :max символов)',
                'integer'  => 'Поле «:attribute» должно быть числом',
                'min.numeric' => 'Минимальное значение «:attribute» — :min',
                'max.numeric' => 'Максимальное значение «:attribute» — :max',
                'exists'   => 'Выбранное мероприятие не найдено — обновите страницу',
            ],
            'uz' => [
                'required' => '«:attribute» maydoni majburiy',
                'email'    => 'Toʻgʻri email kiriting — masalan, name@company.uz',
                'max.string' => '«:attribute» juda uzun (maksimal :max ta belgi)',
                'integer'  => '«:attribute» raqam boʻlishi kerak',
                'min.numeric' => '«:attribute» minimal qiymati — :min',
                'max.numeric' => '«:attribute» maksimal qiymati — :max',
                'exists'   => 'Tanlangan tadbir topilmadi — sahifani yangilang',
            ],
            'en' => [
                'required' => 'The «:attribute» field is required',
                'email'    => 'Enter a valid email — e.g. name@company.uz',
                'max.string' => 'The «:attribute» field is too long (max :max characters)',
                'integer'  => 'The «:attribute» field must be a number',
                'min.numeric' => 'Minimum value for «:attribute» — :min',
                'max.numeric' => 'Maximum value for «:attribute» — :max',
                'exists'   => 'Selected event not found — refresh the page',
            ],
        ];
        return $messages[$locale] ?? $messages['ru'];
    }
}
