<?php

namespace App\Http\Controllers;

use App\Models\RehabPeriod;
use App\Models\RehabUserProgress;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Registrazione con PIN
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age_range' => 'required|string',
            'surgery_time' => 'required|string',
            'surgery_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $pin = $this->generateUniquePin();

        $user = User::create([
            'role' => RoleEnum::USER->value,
            'pin' => Hash::make($pin), // ✅ salvato in modo sicuro
            'data' => [
                'age_range' => $request->age_range,
                'surgery_time' => $request->surgery_time,
                'surgery_type' => $request->surgery_type,
                'movement' => $request->movement,
            ],
        ]);

        $periodToCollocate = 2;
        switch ($request->movement) {
            case "90 gradi":
                $periodToCollocate = 1;
                break;
            case "Nessun movimento":
                $periodToCollocate = 0;
                break;
        }

        $firstPeriod = RehabPeriod::where('order', $periodToCollocate)->first();

        if ($firstPeriod) {
            RehabUserProgress::create([
                'user_id' => $user->id,
                'rehab_period_id' => $firstPeriod->id,
                'is_active' => true,
                'started_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Registrazione completata!',
            'pin' => $pin,
        ], 201);
    }

    /**
     * Login con PIN
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Cerchiamo tra gli utenti USER
        $user = User::where('role', RoleEnum::USER->value)->get()->first(function ($user) use ($request) {
            return Hash::check($request->pin, $user->pin);
        });

        if (!$user) {
            return response()->json(['error' => 'PIN non valido'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Accesso riuscito!',
            'token' => $token,
        ], 200);
    }

    private function generateUniquePin(): string
    {
        do {
            $plainPin = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $used = User::all()->contains(function ($user) use ($plainPin) {
                return Hash::check($plainPin, $user->pin);
            });
        } while ($used);

        return $plainPin;
    }

    //BACKEND

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function backendLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // login solo se è admin
        if (Auth::attempt($credentials)) {
            if (auth()->user()->role !== RoleEnum::ADMIN) {
                Auth::logout();
                return back()->withErrors(['email' => 'Non hai accesso a questa sezione.']);
            }

            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }


        return back()->withErrors([
            'email' => 'Le credenziali non sono valide.',
        ])->withInput();
    }

    public function backendLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
