<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\RoleEnum;
use App\Models\RehabAnswer;
use App\Models\RehabPeriod;
use App\Models\RehabSession;
use App\Models\RehabUserProgress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // — 1. Utenti
        $totalUsers = User::where('role', RoleEnum::USER)->count();

        $ageDistribution = User::where('role', RoleEnum::USER)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.age_range')) AS bucket, COUNT(*) AS count")
            ->groupBy('bucket')
            ->pluck('count', 'bucket');

        $surgeryTypeDist = User::where('role', RoleEnum::USER)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_type')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        $surgeryTimeDist = User::where('role', RoleEnum::USER)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_time')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        $movementDist = User::where('role', RoleEnum::USER)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.movement')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        // — 2. Pre-calcoli per periodo
        $usersPerPeriod = RehabUserProgress::where('is_active', true)
            ->join('rehab_periods','rehab_user_progress.rehab_period_id','=','rehab_periods.id')
            ->select('rehab_periods.title', DB::raw('COUNT(*) AS count'))
            ->groupBy('rehab_periods.title')
            ->pluck('count','title');

        $sessionsStats = RehabSession::join('rehab_periods','rehab_sessions.rehab_period_id','=','rehab_periods.id')
            ->select(
                'rehab_periods.title',
                DB::raw('COUNT(*) AS total'),
                DB::raw('SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) AS completed')
            )
            ->groupBy('rehab_periods.title')
            ->get()
            ->mapWithKeys(fn($r) => [
                $r->title => ['total' => $r->total, 'completed' => $r->completed]
            ]);

        // conteggio questionari inviati = sessioni completate **e** che hanno almeno un answer
        $questionnairesPerPeriod = RehabSession::where('is_completed', true)
            ->whereHas('answers')
            ->with('period')
            ->get()
            ->groupBy(fn($s) => $s->period->title)
            ->map->count();

        // — 3. Costruisco l’array periods
        $periods = RehabPeriod::orderBy('order')->get()->map(function($period) use ($usersPerPeriod, $sessionsStats, $questionnairesPerPeriod) {
            $title        = $period->title;
            $userCount    = $usersPerPeriod->get($title, 0);
            $sessData     = $sessionsStats->get($title, ['total' => 0, 'completed' => 0]);

            // ➡️ Calcolo la media per utente
            $perUser = RehabSession::where('rehab_period_id', $period->id)
                ->select(
                    'user_id',
                    DB::raw('COUNT(*) AS total_sessions'),
                    DB::raw("COUNT(DISTINCT DATE(started_at)) AS days_active")
                )
                ->groupBy('user_id')
                ->get();

            $perUserAvgs = $perUser->map(fn($row) => $row->days_active
                ? $row->total_sessions / $row->days_active
                : 0
            );

            $avgDaily = $perUserAvgs->count()
                ? round($perUserAvgs->avg(), 2)
                : 0;

            // domande + distribuzioni FILTRANDO SOLO SESSIONI COMPLETATE
            $questions = $period->questions->map(function($q) use ($period) {
                $dist = RehabAnswer::join('rehab_sessions','rehab_answers.rehab_session_id','=','rehab_sessions.id')
                    ->where('rehab_sessions.rehab_period_id', $period->id)
                    ->where('rehab_sessions.is_completed', true)           // ← filtro
                    ->where('rehab_answers.rehab_question_id', $q->id)
                    ->select('rehab_answers.answer', DB::raw('COUNT(*) as count'))
                    ->groupBy('rehab_answers.answer')
                    ->pluck('count','answer')
                    ->toArray();

                return [
                    'id'            => $q->id,
                    'title'         => $q->title,
                    'question'      => $q->question,
                    'type'          => $q->type,
                    'options'       => $q->type === 'choice' ? $q->options : null,
                    'distribution'  => $dist,
                ];
            });

            return [
                'title'              => $title,
                'users'              => $userCount,
                'sessions'           => $sessData,
                'questionnaires'     => $questionnairesPerPeriod->get($title, 0), // ← badge
                'avg_daily_sessions' => $avgDaily,
                'questions'          => $questions,
            ];
        });

        // — 4. Torno la view con tutti i dati
        return view('admin.dashboard', compact(
            'totalUsers',
            'ageDistribution',
            'surgeryTypeDist',
            'surgeryTimeDist',
            'movementDist',
            'periods'
        ));
    }
}
