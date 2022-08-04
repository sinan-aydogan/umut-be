<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($request->email);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $token->plainTextToken,
        ], 201);
    }

    /*Login*/
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken($request->email);
            return response()->json([
                'message' => 'User logged in successfully',
                'data' => $token->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 403);
        }


    }
}
