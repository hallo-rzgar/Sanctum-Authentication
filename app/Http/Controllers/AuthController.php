<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

//        $this->authorize('create', User::class);
        if ( $request->role =='admin' || $request->role =='manager' ) {
            if ( auth()->user()->role != 'admin'){
                dd('Your access denied');
            }

        }
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,manager,employee',
            'manager_id' => 'nullable|integer|exists:users,id',
            'birthdate' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'gender' => 'nullable|string|in:male,female,other',
            'hired_date' => 'nullable|date',
            'job_title' => 'nullable|string|max:255',
            'profile_logo' => 'nullable|images|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validatedData->errors()
            ], 400);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'birthdate' => $request->birthdate,
            'salary' => $request->salary,
            'gender' => $request->gender,
            'hired_date' => $request->hired_date,
            'job_title' => $request->job_title,
            'manager_id' => auth()->user()->id,
            'password' => bcrypt($request->password),
        ]);
        // Handle profile logo upload

        if ($request->hasFile('images')) {
            // Save the file to the storage/app/public/images directory

            $images = $request->file('images');
            $filename = time() . '.' . $images->getClientOriginalExtension();
            $path = $images->storeAs('public/images', $filename);
            $user->profile_logo = $filename;
        } else {
            $imagePath = null;
        }


        $user->save();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * Login a user
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = $request->user();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    }

    /**
     * Logout a user (Revoke the token)
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ], 200);
    }



}
