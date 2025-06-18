<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    use ApiResponseTrait;
  public function register(Request $request)
{
     $request->validate([
    'name'  => 'required|string|max:100',
    'email'      => 'required|email|unique:users,email|max:100',
    'password'   => 'required|string|min:8',
]);
    $user = User::create([
    'name'  => $request->name,
    'email'      => $request->email,
    'password'   => Hash::make($request->password),
]);

       return response()->json(['user'=> $user, 'message' => 'User registered successfully'], 201);
}


}
