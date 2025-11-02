<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Mail\TwoFAMail;

// Model
use App\Models\User;
use App\Models\MstRules;

class AuthController extends Controller
{
    public function login(Request $request){
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'captcha_input' => 'required|in:' . session('captcha_code'),
        ], [
            'captcha_input.required' => 'Kode CAPTCHA harus diisi.',
            'captcha_input.in' => 'Kode CAPTCHA yang dimasukkan tidak sesuai.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            if ($user->is_active != 1) {
                Auth::logout();
                return redirect()->route('login')->with('fail', 'Your Account Is Inactive');
            }

            // ✅ If user has 2FA enabled
            if ($user->is_two_fa == 1) {
                // Get expiration time from rules table
                $expiredSeconds = MstRules::where('rule_name', 'Expired 2FA Code (in second)')
                    ->value('rule_value') ?? 120; // default 120s if not found

                // Generate 6-digit random code
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                // Update user with code and expiry time
                $user->update([
                    'two_fa_code' => $code,
                    'two_fa_expired_at' => Carbon::now()->addSeconds($expiredSeconds),
                ]);

                // Send to email
                $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                if ($development == 1) {
                    $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                } else {
                    $toemail = $user->email;
                }
                Mail::to($toemail)->send(new TwoFAMail($user, $code, $expiredSeconds));

                Auth::logout(); // logout temporarily until code verified
                session(['pending_2fa_user' => $user->email]); // keep email to verify later

                return redirect()->route('verify.2fa')->with('info', 'A 2FA code has been sent to your email.');
            }

            // If no 2FA required
            return redirect()->route('dashboard')->with('success', 'Successfully Entered The Application');
        }

        return redirect()->route('login')->with('fail', 'Wrong Email or Password');
    }

    public function show2fa()
    {
        if (!session()->has('pending_2fa_user')) {
            return redirect()->route('login');
        }
        return view('auth.two_fa');
    }

    public function verify2fa(Request $request)
    {
        $email = session('pending_2fa_user');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('fail', 'Invalid session.');
        }

        if ($user->two_fa_code === $request->two_fa_code && Carbon::now()->lt($user->two_fa_expired_at)) {
            // ✅ Successful verification
            Auth::login($user);
            session()->forget('pending_2fa_user');

            // clear code
            $user->update([
                'two_fa_code' => null,
                'two_fa_expired_at' => null,
            ]);

            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->with('fail', 'Invalid or expired 2FA code.');
    }

    public function resend2fa()
    {
        $email = session('pending_2fa_user');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('fail', 'Invalid session.');
        }

        $expiredSeconds = MstRules::where('rule_name', 'Expired 2FA Code (in second)')
            ->value('rule_value') ?? 120;

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'two_fa_code' => $code,
            'two_fa_expired_at' => Carbon::now()->addSeconds($expiredSeconds),
        ]);
        
        // Send to email
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        if ($development == 1) {
            $toemail = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        } else {
            $toemail = $user->email;
        }
        Mail::to($toemail)->send(new TwoFAMail($user, $code, $expiredSeconds));

        return back()->with('info', 'A new 2FA code has been sent to your email.');
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('login')->with('success','Success Logout');
    }
}
