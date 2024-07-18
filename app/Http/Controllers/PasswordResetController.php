<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordOTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);
        $email = $request->email;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $otp, 'created_at' => Carbon::now()]
        );

        Mail::to($email)->send(new ResetPasswordOTP($otp));

        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $otpRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$otpRecord || Carbon::parse($otpRecord->created_at)->addMinutes(30)->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password has been reset successfully.']);
    }
}
