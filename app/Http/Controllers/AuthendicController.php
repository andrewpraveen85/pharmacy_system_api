<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Http\Library\ApiHelpers;

class AuthendicController extends Controller
{
    use ApiHelpers;
    
    public function login(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $fields['username'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return $this->onError(404, 'User Not Found');
        }
        if($user->role== 1){
            $token = $user->createToken('auth_token', ['admin'])->plainTextToken;
        }
        if($user->role== 2){
            $token = $user->createToken('auth_token', ['manager'])->plainTextToken;
        }
        if($user->role== 3){
            $token = $user->createToken('auth_token', ['cashier'])->plainTextToken;
        }
        return $this->onSuccess($token, 'Login Success');
    }
}
