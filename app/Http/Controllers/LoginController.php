<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Implements a simple email/password login.
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('web')->attempt($validatedData)) {
            $token = auth()->guard('web')->user()->createToken('default');

            return response()->json($token, 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}