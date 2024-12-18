<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthOtpController extends Controller
{
    public function generate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email'
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.exists'   => 'Alamat email tidak terdaftar di sistem kami.'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $verificationCode = $this->generateOtp($request->email);
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Gagal menghasilkan kode OTP. Silakan coba lagi.');
        }

        // Mail::to($request->email)->send(new OTPMail($verificationCode->otp));

        $message = "Kode OTP berhasil dikirim ke email anda " . $verificationCode->otp;

        return redirect()->route('otp.verification', ['user_id' => $verificationCode->user_id])->with('success',  $message);
    }

    public function generateOtp($email)
    {
        $user = User::where('email', $email)->first();

        $verificationCode = VerificationCode::where("user_id", $user->id)->latest()->first();

        $now = Carbon::now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            return $verificationCode;
        }

        return VerificationCode::create([
            "user_id"   => $user->id,
            "otp"       => rand(1234, 9999),
            "expire_at" => Carbon::now()->addMinutes(10)
        ]);
    }

    public function verification($user_id)
    {
        return view('auth.verify')->with([
            'user_id' => $user_id
        ]);
    }

    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required'
        ]);

        $verificationCode   = VerificationCode::where('user_id', $request->user_id)->where('otp', $request->otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Kode OTP salah');
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            return redirect()->route('login')->with('error', 'Kode OTP tidak berlaku');
        }

        $user = User::whereId($request->user_id)->first();

        if ($user) {
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            Auth::login($user);

            return redirect()->route('home');
        }

        return redirect()->route('otp.verification')->with('error', 'Kode OTP salah');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
