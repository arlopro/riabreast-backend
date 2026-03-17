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
            'pin' => Hash::make($pin),
            'pin_hmac' => User::hmacPin($pin),
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

        $hmac = User::hmacPin($request->pin);

        // Lookup diretto tramite HMAC (O(1) con indice)
        $user = User::where('role', RoleEnum::USER->value)
            ->where('pin_hmac', $hmac)
            ->first();

        // Fallback per utenti esistenti che non hanno ancora pin_hmac (lazy migration)
        if (!$user) {
            $user = User::where('role', RoleEnum::USER->value)
                ->whereNull('pin_hmac')
                ->get()
                ->first(fn ($u) => Hash::check($request->pin, $u->pin));

            if ($user) {
                $user->update(['pin_hmac' => $hmac]);
            }
        }

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
            $hmac = User::hmacPin($plainPin);
            $used = User::where('pin_hmac', $hmac)->exists();
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
