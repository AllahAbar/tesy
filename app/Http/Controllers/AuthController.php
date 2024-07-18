<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
//---add recently
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordOTP;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    // register
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        //assign default role 
        $user->roles()->attach(2); // 3: id role->member

        return response()->json([
            'message' => 'Successfully registered',
        ], 201);
    }

    public function login()
    {
        $credentials = request(['name', 'password']);
        //if (! $token = auth()->attempt($credentials)) {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
    
    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message'=> 'Successfully logout!!']);
        }catch(\Exception $e){
            return response()->json(['error'=>'Fail logout']);
        }
        //JWTAuth::logout();
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return $this->respondWithToken($newToken);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to refresh token, please try again.'], 500);
        }
    }
    protected function respondWithToken($token)
    {
        $user = JWTAuth::user();
        $roles = $user->roles; // Lấy tất cả các vai trò của người dùng
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    //Method Forgot password
    public function sendResetLinkEmail(Request $request)
    {
        //Providing user's email 
        //if email valid, the systen's will generate otp and give it to genereated email
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' => 'Reset password  OTP sent to your email'], 200)
                    : response()->json(['error' => 'Unable to send reset otp'], 400);
    }

    //after having information input (email, otp, new_password)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'otp' => 'required|string',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? response()->json(['message' => 'Password reset successfully'], 200)
                    : response()->json(['error' => 'Unable to reset password'], 400);
    }

}
