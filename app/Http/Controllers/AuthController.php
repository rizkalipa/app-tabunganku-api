<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login (Request $request): JsonResponse
    {
        $userData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$userData['email'])->first();

        if (!$user || !Hash::check($userData['password'],$user->password)) {
            return $this->sendResponse([], 'Invalid credentials.', 401, false);
        }

        return $this->sendResponse(
            ['access_token' => $user->createToken($user->name.'-AuthToken')->plainTextToken],
            'Login success.'
        );
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validator->fails()) {
                return $this->sendResponse([], 'Validation error.', 401, false, $validator->errors());
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return $this->sendResponse(['token' => $user->createToken("API TOKEN")->plainTextToken], 'Register success.');

        } catch (\Throwable $th) {
            return $this->sendResponse([], $th->getMessage(), 500, false);
        }

    }
}
