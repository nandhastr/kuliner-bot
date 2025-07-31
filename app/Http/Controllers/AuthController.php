<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'otp_code' => rand(100000, 999999),
            'otp_expired_at' => now()->addMinutes(10),
        ]);

        Mail::raw("OTP Anda: {$user->otp_code}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Anda');
        });
        
        return response()->json([

            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk mendapatkan OTP.',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Login gagal atau akun tidak ditemukan'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'token_type' => 'bearer',
            'token' => $token
        ]);
    }

    public function verify2FA(Request $request)
    {
        $request->validate([
            'otp_code' => 'required',
            'email'    => 'required|email',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expired_at', '>=', now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'OTP tidak valid'], 401);
        }

        return response()->json(['message' => 'Two Factor Authentication berhasil']);
    }

    
}
