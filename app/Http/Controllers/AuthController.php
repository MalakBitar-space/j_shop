<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
class AuthController extends Controller
{
    use ApiResponseTrait;


public function register(Request $request)
{
    $request->merge([
        'email' => strtolower($request->email),
    ]);

    $request->validate([
        'name'     => 'required|string|max:100',
        'email' => [
    'required',
    'email',
    'max:100',
    'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/'
],
 // بدون unique هون
        'password' => 'required|string|min:8',
    ]);

    try {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
    } catch (QueryException $e) {
        if ($e->getCode() == '23000') {
            return $this->errorResponse(
                'هذا البريد الإلكتروني مستخدم بالفعل',
                409
            );
        }

        return $this->errorResponse(
            'حدث خطأ أثناء إنشاء الحساب',
            500
        );
    }

    return $this->successResponse(
        ['user' => $user],
        'تم تسجيل المستخدم بنجاح',
        201
    );
}


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $email = strtolower($request->email); // ← التصغير

    $user = User::where('email', $email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return $this->errorResponse('بيانات الدخول غير صحيحة', 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->successResponse([
        'user' => $user,
        'token' => $token
    ], 'تم تسجيل الدخول بنجاح');
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return $this->successResponse(
        message: 'تم تسجيل الخروج بنجاح',
        statusCode: 200
    );
}


}