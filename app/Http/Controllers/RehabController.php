<?php
namespace App\Http\Controllers;

use App\Models\RehabAnswer;
use App\Models\RehabExtra;
use App\Models\RehabPeriod;
use App\Models\RehabQuestion;
use App\Models\RehabSession;
use App\Models\RehabUserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RehabController extends Controller
{
    public function currentPeriod(Request $request)
    {
        $user = $request->user();

        $progress = $user->currentProgress()?->load('rehabPeriod');

        if (!$progress || !$progress->rehabPeriod) {
            return response()->json(['error' => 'Nessun periodo attivo trovato'], 404);
        }

        return response()->json([
            'id' => $progress->rehabPeriod->id,
            'period' => $progress->rehabPeriod->p_number,
            'title' => $progress->rehabPeriod->title,
            'description' => $progress->rehabPeriod->description,
            'video_youtube_id' => $progress->rehabPeriod->video_youtube_id,
        ]);
    }

    public function startSession(Request $request)
    {
        $user = $request->user();
        $currentProgress = $user->currentProgress();

        if (!$currentProgress) {
            return response()->json(['error' => 'Nessun periodo attivo'], 404);
        }

        // 🔄 Evitiamo sessioni duplicate non terminate
        $existing = RehabSession::where('user_id', $user->id)
            ->where('rehab_period_id', $currentProgress->rehab_period_id)
            ->where('is_completed', false)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Sessione già avviata']);
        }

        $session = RehabSession::create([
            'user_id' => $user->id,
            'rehab_period_id' => $currentProgress->rehab_period_id,
            'started_at' => now(),
            'is_completed' => false,
        ]);

        return response()->json(['message' => 'Sessione avviata', 'session_id' => $session->id]);
    }

    public function endSession(Request $request)
    {
        $user = $request->user();

        $session = RehabSession::where('user_id', $user->id)
            ->where('is_completed', false)
            ->latest('started_at')
            ->first();

        if (!$session) {
            $currentProgress = $user->currentProgress();

            if (!$currentProgress) {
                return response()->json(['error' => 'Nessun periodo attivo'], 404);
            }

            $session = RehabSession::create([
                'user_id' => $user->id,
                'rehab_period_id' => $currentProgress->rehab_period_id,
                'started_at' => now(),
                'completed_at' => now(),
                'is_completed' => true,
            ]);
        }

        return response()->json(['message' => 'Sessione completata']);
    }

    public function questionnaire()
    {
        $user = auth()->user();

        $currentProgress = $user->currentProgress();

        if (!$currentProgress) {
            return response()->json(['error' => 'Nessun periodo attivo'], 404);
        }

        $questions = $currentProgress->rehabPeriod->questions()->select('id', 'title', 'question', 'type', 'options', 'labels')->get();

        return response()->json($questions);
    }

    public function submitAnswers(Request $request)
    {
        $user = auth()->user();
        $currentProgress = $user->currentProgress();

        if (!$currentProgress) {
            return response()->json(['error' => 'Nessun periodo attivo'], 404);
        }

        $session = $user->rehabSessions()->latest()->first(); // supponiamo che la sessione sia già creata

        if (!$session) {
            $currentProgress = $user->currentProgress();

            if (!$currentProgress) {
                return response()->json(['error' => 'Nessun periodo attivo'], 404);
            }

            $session = RehabSession::create([
                'user_id' => $user->id,
                'rehab_period_id' => $currentProgress->rehab_period_id,
                'started_at' => now(),
                'completed_at' => now(),
                'is_completed' => true,
            ]);
        }

        $answers = $request->all();
        $blocked = false;

        foreach ($answers as $questionId => $value) {
            $question = RehabQuestion::find($questionId);

            // Salva la risposta
            RehabAnswer::create([
                'rehab_session_id' => $session->id,
                'rehab_question_id' => $questionId,
                'answer' => is_string($value) ? $value : (string) $value,
            ]);

            // Valuta se blocca il passaggio
            if ($question->block_if) {
                $blockIf = $question->block_if;

                if (
                    ($blockIf['type'] === 'scale' && $value > $blockIf['greater_than']) ||
                    ($blockIf['type'] === 'choice' && $value === $blockIf['equals'])
                ) {
                    $blocked = true;
                }
            }
        }

        $noMorePeriod = false;

        // Se non è bloccato, possiamo passare al prossimo periodo
        if (!$blocked) {
            Log::info($currentProgress->rehabPeriod->order);
            $nextPeriod = \App\Models\RehabPeriod::where('order', '>', $currentProgress->rehabPeriod->order)
                ->orderBy('order')
                ->first();

            if ($nextPeriod) {
                // 🔹 Segna il periodo attuale come completato
                $currentProgress->update([
                    'is_active' => false,
                    'completed_at' => now(),
                ]);

                // 🔹 Crea un nuovo periodo attivo
                $user->rehabProgresses()->create([
                    'rehab_period_id' => $nextPeriod->id,
                    'is_active' => true,
                    'started_at' => now(),
                ]);
            }else{
                $noMorePeriod = true;
            }
        }

        return response()->json(['blocked' => $blocked, 'noMorePeriod' => $noMorePeriod]);
    }


    public function goToPreviousPeriod(Request $request)
    {
        $user = auth()->user();
        $currentProgress = $user->currentProgress();

        if (!$currentProgress) {
            return response()->json(['error' => 'Nessun periodo attivo'], 404);
        }

        $currentPeriod = $currentProgress->rehabPeriod;

        // Trova il periodo precedente (con order inferiore)
        $previousPeriod = RehabPeriod::where('order', '<', $currentPeriod->order)
            ->orderByDesc('order')
            ->first();

        if (!$previousPeriod) {
            return response()->json(['error' => 'Nessun periodo precedente'], 404);
        }

        // Aggiorna il periodo corrente dell'utente
        $currentProgress->update([
            'rehab_period_id' => $previousPeriod->id
        ]);

        return response()->json(['message' => 'Tornato al periodo precedente']);
    }

    public function dailySessions(Request $request)
    {
        $user = $request->user();

        $data = $user->rehabSessions()
            ->selectRaw('DATE(started_at) as date, COUNT(*) as count')
            ->groupByRaw('DATE(started_at)')
            ->orderByRaw('DATE(started_at)')
            ->get()
            ->map(fn($row) => ['date' => $row->date, 'count' => (int) $row->count]);

        return response()->json($data);
    }

    public function extras()
    {
        return response()->json(RehabExtra::all());
    }

    public function extraDetail($id)
    {
        $extra = RehabExtra::findOrFail($id);

        return response()->json([
            'id' => $extra->id,
            'title' => $extra->title,
            'description' => $extra->description,
            'video_youtube_id' => $extra->video_youtube_id,
        ]);
    }


}

