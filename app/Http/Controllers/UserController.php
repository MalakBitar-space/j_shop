<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function showProfile(Request $request)
    {
        $user = $request->user();

        $photoUrl = $user->getFirstMediaUrl('profile');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-avatar.jpg');
        }

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'photo_url' => $photoUrl,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 'تم جلب بيانات الملف الشخصي بنجاح');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'email' => [
                'nullable',
                'email',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
                'unique:users,email,' . $user->id,
            ],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 422);
        }

        $data = $validator->validated();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = strtolower($data['email']);
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if (isset($data['phone_number'])) {
            $user->phone_number = $data['phone_number'];
        }
        if (isset($data['address'])) {
            $user->address = $data['address'];
        }

        if ($request->hasFile('photo')) {
            $user->clearMediaCollection('profile');
            $user->addMediaFromRequest('photo')->toMediaCollection('profile');
        }

        $user->save();

        $photoUrl = $user->getFirstMediaUrl('profile');
        if (empty($photoUrl)) {
            $photoUrl = asset('images/default-avatar.jpg');
        }

        return $this->successResponse([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'photo_url' => $photoUrl,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 'تم تحديث الملف الشخصي بنجاح');
    }

    public function deleteProfileImage(Request $request)
    {
        $user = $request->user();

        if ($user->hasMedia('profile')) {
            $user->clearMediaCollection('profile');
            return $this->successResponse(null, 'تم حذف صورة الملف الشخصي بنجاح');
        }

        return $this->errorResponse('لا توجد صورة لحذفها', 404);
    }
}
