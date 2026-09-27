<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        // Полл должен быть активен
        abort_unless($poll->is_active && !$poll->hasEnded(), 403, 'Голосование завершено');

        $data = $request->validate([
            'option_index' => 'required|integer|min:0',
        ]);

        $optionCount = count($poll->options ?? []);
        abort_if($data['option_index'] >= $optionCount, 422, 'Неверный вариант');

        $ipHash = hash('sha256', (string) $request->ip() . config('app.key'));

        // Дедуп через unique constraint
        try {
            PollVote::create([
                'poll_id'      => $poll->id,
                'option_index' => $data['option_index'],
                'ip_hash'      => $ipHash,
                'session_hash' => hash('sha256', (string) $request->session()->getId()),
                'voted_at'     => now(),
            ]);
            DB::table('polls')->where('id', $poll->id)->increment('total_votes');
        } catch (\Illuminate\Database\QueryException $e) {
            // Уже голосовал — возвращаем результаты без ошибки
            if (str_contains($e->getMessage(), 'UNIQUE') || $e->errorInfo[1] == 1062) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['ok' => false, 'message' => 'Вы уже голосовали в этом опросе', 'results' => $poll->fresh()->getVoteCounts(), 'total' => $poll->fresh()->total_votes]);
                }
                return back()->with('warning', 'Вы уже голосовали в этом опросе');
            }
            throw $e;
        }

        $poll = $poll->fresh();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Спасибо за голос!',
                'results' => $poll->getVoteCounts(),
                'total'   => $poll->total_votes,
            ]);
        }
        return back()->with('success', 'Спасибо за голос!');
    }
}
