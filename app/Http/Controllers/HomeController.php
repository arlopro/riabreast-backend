<?php
namespace App\Http\Controllers;

use App\Models\RehabUserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Utente non autenticato'], 401);
        }

        $progress = $user->currentProgress()?->load('rehabPeriod');

        if (!$progress) {
            return response()->json(['error' => 'Nessun periodo attivo trovato.'], 404);
        }

        $todaySessions = $user->rehabSessions()
            ->whereDate('started_at', now()->toDateString())
            ->count();

        return response()->json([
            'period' => [
                'id' => $progress->rehabPeriod->id,
                'title' => $progress->rehabPeriod->title,
                'order' => $progress->rehabPeriod->p_number,
            ],
            'today_sessions' => $todaySessions,
        ]);
    }
}

