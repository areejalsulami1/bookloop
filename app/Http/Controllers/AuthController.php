<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        $request->validate([
            'firebase_token' => 'required',
        ]);

        try {
            // ✅ التحقق من التوكن المرسل من العميل
            $verifiedIdToken = $this->auth->verifyIdToken($request->firebase_token);
            $uid = $verifiedIdToken->claims()->get('sub'); // uid من التوكن

            // ✅ جلب بيانات المستخدم من Firebase
            $firebaseUser = $this->auth->getUser($uid);

            // ✅ البحث عن المستخدم بقاعدة البيانات (أو إنشاؤه إن لم يوجد)
            $user = User::firstOrCreate(
                ['email' => $firebaseUser->email],
                ['name' => $firebaseUser->displayName ?? 'No Name']
            );

            // ✅ إنشاء توكن باستخدام Sanctum
            $token = $user->createToken('firebase-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);

        } catch (FailedToVerifyToken $e) {
            Log::error('[Firebase] فشل التحقق من التوكن: ' . $e->getMessage());
            return response()->json(['message' => 'رمز التحقق من Firebase غير صالح'], 401);
        } catch (\Throwable $e) {
            Log::error('[Login error] ' . $e->getMessage());
            return response()->json(['message' => 'فشل تسجيل الدخول'], 500);
        }
    }
}
