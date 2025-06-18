<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\RoleEnum;
use App\Models\RehabAnswer;
use App\Models\RehabPeriod;
use App\Models\RehabSession;
use App\Models\RehabUserProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ageRange    = $request->input('age_range');
        $surgeryType = $request->input('surgery_type');
        $surgeryTime = $request->input('surgery_time');

        $baseUsers = User::where('role', RoleEnum::USER)
            ->when($ageRange,    fn($q) => $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.age_range')) = ?", [$ageRange]))
            ->when($surgeryType, fn($q) => $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_type')) = ?", [$surgeryType]))
            ->when($surgeryTime, fn($q) => $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_time')) = ?", [$surgeryTime]));

        $userIds = (clone $baseUsers)->pluck('id');

        // — 1. Utenti
        $totalUsers = $userIds->count();

        $ageDistribution = (clone $baseUsers)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.age_range')) AS bucket, COUNT(*) AS count")
            ->groupBy('bucket')
            ->pluck('count', 'bucket');

        $surgeryTypeDist = (clone $baseUsers)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_type')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        $surgeryTimeDist = (clone $baseUsers)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_time')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        $movementDist = (clone $baseUsers)
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.movement')) AS answer, COUNT(*) AS count")
            ->groupBy('answer')
            ->pluck('count','answer');

        // — 2. Pre-calcoli per periodo
        $usersPerPeriod = RehabUserProgress::where('is_active', true)
            ->whereIn('user_id', $userIds)
            ->join('rehab_periods','rehab_user_progress.rehab_period_id','=','rehab_periods.id')
            ->select('rehab_periods.title', DB::raw('COUNT(*) AS count'))
            ->groupBy('rehab_periods.title')
            ->pluck('count','title');

        $sessionsStats = RehabSession::whereIn('rehab_sessions.user_id', $userIds)
            ->join('rehab_periods','rehab_sessions.rehab_period_id','=','rehab_periods.id')
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

        // conteggio questionari inviati = sessioni con almeno 1 risposta
        $questionnairesPerPeriod = RehabAnswer::join('rehab_sessions','rehab_answers.rehab_session_id','=','rehab_sessions.id')
            ->whereIn('rehab_sessions.user_id', $userIds)
            ->join('rehab_periods','rehab_sessions.rehab_period_id','=','rehab_periods.id')
            ->select('rehab_periods.title', DB::raw('COUNT(DISTINCT rehab_answers.rehab_session_id) AS count'))
            ->groupBy('rehab_periods.title')
            ->pluck('count','title');

        // — 3. Costruisco l’array periods
        $periods = RehabPeriod::orderBy('order')->get()->map(function($period) use ($usersPerPeriod, $sessionsStats, $questionnairesPerPeriod, $userIds) {
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

            // domande + distribuzioni filtrando gli utenti
            $questions = $period->questions->map(function($q) use ($period, $userIds) {
                $dist = RehabAnswer::join('rehab_sessions','rehab_answers.rehab_session_id','=','rehab_sessions.id')
                    ->whereIn('rehab_sessions.user_id', $userIds)
                    ->where('rehab_sessions.rehab_period_id', $period->id)
                    ->where('rehab_answers.rehab_question_id', $q->id)
                    ->select('rehab_answers.answer', DB::raw('COUNT(*) as count'))
                    ->groupBy('rehab_answers.answer')
                    ->pluck('count','answer')
                    ->toArray();

                return [
                    'id'           => $q->id,
                    'title'        => $q->title,
                    'question'     => $q->question,
                    'type'         => $q->type,
                    'options'      => $q->type === 'choice' ? $q->options : null,
                    'distribution' => $dist,
                ];
            });

            return [
                'title'              => $title,
                'users'              => $userCount,
                'sessions'           => $sessData,
                'questionnaires'     => $questionnairesPerPeriod->get($title, 0),
                'avg_daily_sessions' => $avgDaily,
                'questions'          => $questions,
            ];
        });

        // opzioni per i filtri (tutte le possibili risposte)
        $ageOptions = User::where('role', RoleEnum::USER)
            ->selectRaw("DISTINCT JSON_UNQUOTE(JSON_EXTRACT(data, '$.age_range')) AS val")
            ->pluck('val');
        $surgeryTypeOptions = User::where('role', RoleEnum::USER)
            ->selectRaw("DISTINCT JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_type')) AS val")
            ->pluck('val');
        $surgeryTimeOptions = User::where('role', RoleEnum::USER)
            ->selectRaw("DISTINCT JSON_UNQUOTE(JSON_EXTRACT(data, '$.surgery_time')) AS val")
            ->pluck('val');

        // — 4. Torno la view con tutti i dati
        return view('admin.dashboard', compact(
            'totalUsers',
            'ageDistribution',
            'surgeryTypeDist',
            'surgeryTimeDist',
            'movementDist',
            'periods',
            'ageOptions',
            'surgeryTypeOptions',
            'surgeryTimeOptions',
            'ageRange',
            'surgeryType',
            'surgeryTime'
        ));
    }
}
