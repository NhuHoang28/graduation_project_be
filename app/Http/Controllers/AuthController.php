<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * The function registers a new user, validates the input, creates a token for the user, and
     * returns a JSON response with the access token and token type.
     * 
     * @param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an HTTP request. It contains information about the request such as the HTTP method,
     * headers, and input data.
     * 
     * @return JSON response with an access token and token type.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:255',
            'role_id' => 'required|alpha_num',
            'account_id' => 'required|alpha_num',
            'password' => 'required|min:6|max:255',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'role_id' => $validated['role_id'],
            'account_id' => $validated['account_id'],
            'password' => \Hash::make($validated['password']),
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * This function handles user login authentication and generates a token for authorized access to
     * the application.
     * 
     * @param Request request  is an instance of the Illuminate\Http\Request class, which
     * represents an HTTP request. It contains information about the request such as the HTTP method,
     * headers, and any data that was sent with the request. In this case, it is used to retrieve the
     * username and password from the request body.
     * 
     * @return JSON response with a message indicating
     * invalid credentials and a 401 status code is returned.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('token', [$user->role_id])->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
        return response()->json(['message' => 'Invalid login credentials'], 401);
    }

    /**
     * The function logs out the authenticated user by deleting their access tokens.
     * 
     * @return JSON
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
