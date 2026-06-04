<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MembershipApplication;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function membership(Request $request)
    {
        $data = $request->validate([
            'company'  => 'required|string|max:255',
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|string|max:50',
            'category' => 'nullable|string|max:255',
            'volume'   => 'nullable|string|max:255',
            'message'  => 'nullable|string|max:5000',
        ]);

        $data['source_page'] = substr((string) $request->headers->get('referer', ''), 0, 100);

        // UTM из сессии (запомнил TrackPageView)
        $utm = session('utm', []);
        $data['utm_source']   = $utm['utm_source']   ?? null;
        $data['utm_medium']   = $utm['utm_medium']   ?? null;
        $data['utm_campaign'] = $utm['utm_campaign'] ?? null;

        MembershipApplication::create($data);

        return back()->with('success', 'Заявка отправлена! Менеджер свяжется в течение рабочего дня.');
    }

    public function eventRegister(Request $request, ?string $slug = null)
    {
        $data = $request->validate([
            'event_id'        => 'nullable|integer|exists:events,id',
            'company'         => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'phone'           => 'required|string|max:50',
            'attendees_count' => 'nullable|integer|min:1|max:20',
        ]);

        if ($slug && !isset($data['event_id'])) {
            $event = Event::where('slug', $slug)->first();
            if ($event) {
                $data['event_id'] = $event->id;
                $data['event_name'] = $event->getTranslation('title', 'ru', false);
            }
        }

        EventRegistration::create($data);

        return back()->with('success', 'Регистрация принята! Координатор свяжется для подтверждения.');
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'topic'   => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Сообщение отправлено! Мы ответим в течение рабочего дня.');
    }
}
