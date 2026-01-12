<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['status' => false, "message" => "Validation error", "errors" => $validatedData->errors()->all()], 422);
            }

            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Auto login logic with safety check
            if ($request->hasSession()) {
                Auth::login($user);
                $request->session()->regenerate();
            }

            // Return the user in the response
            return response()->json(['status' => true, 'message' => 'User registered successfully', 'user' => $user], 201);

        } catch (\Throwable $th) {
            Log::error("Registration Error: " . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => config('app.debug') ? $th->getMessage() : 'Internal Server Error'
            ], 500);
        }
    }

    public function login(Request $request){
         // Validate the request
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['status' => false, "message" => "Validation error", "errors" => $validatedData->errors()->all()], 401);
        }

        // Attempt to find the user
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $request->session()->regenerate();
            return response()->json(['status' => true, 'message' => 'User logged in successfully', 'user' => $user], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => true, 'message' => 'User logged out successfully'], 200);
    }

    public function user(Request $request){
       
          return response()->json([
            'status' => true,
            'user' => $request->user(),
        ], 200);
    }
}
