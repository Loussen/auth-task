<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ApiController extends Controller
{
    // Login
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['username', 'password']);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !auth()->attempt($credentials)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Username or password is incorrect'
            ], 401);
        }

        $data = [
            'token'  => $user->createToken("api-token")->plainTextToken,
            'user' => $user,
            'token_type'    => 'bearer',
            'expires_in'    => 600
        ];

        return $this->successResponse($data,"Success Login");
    }

    // New user registration
    public function register(RegistrationRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $credentials['password'] = Hash::make($credentials['password']);
        $credentials['registration_date'] = now();

        $user = User::create($credentials);

        $data = [
            'user' => $user
        ];

        return $this->successResponse($data,"Success Registration");
    }

    // Return user information
    public function info(): JsonResponse
    {
        $data = [
            'user' => auth()->user()
        ];

        return $this->successResponse($data,"Data has been found");
    }

    // Edit user information
    public function update(UpdateRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::where('id', auth()->id())->first();
        $user->update($credentials);

        $data = [
            'user' => $user
        ];

        return $this->successResponse($data,"Data has been updated");
    }

    public function latency(): JsonResponse
    {
        $pingHost = 'google.com'; // replace with your desired host
        exec("ping -c 1 " . $pingHost, $output, $status);
        if ($status == 0) {
            // extract the round trip time (RTT) from the output
            preg_match('/time=([0-9.]+) ms/', implode("\n", $output), $matches);
            $latency = isset($matches[1]) ? (float)$matches[1] : null;
            $data = $latency;
            return $this->successResponse($data,"Success ping");
        } else {
            return $this->errorResponse('Ping failed!',500);
        }
    }

    // Delete current token
    public function token(): JsonResponse
    {
        auth()->logout();

        return $this->successResponse([],"Successfully logged out");
    }
}

