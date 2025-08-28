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
        'password' => 'required|string|min:8',
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    try {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
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
        ['user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]],
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

    $email = strtolower($request->email);

    $user = User::where('email', $email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return $this->errorResponse('بيانات الدخول غير صحيحة', 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return $this->successResponse([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ],
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