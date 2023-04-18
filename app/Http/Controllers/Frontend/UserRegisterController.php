<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserRegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validator::make($request, [
        //     'name' => 'required|string|max:255',
        //     'phone' => 'required|unique:users',
        //     'password' => 'required|string|min:6|confirmed',
        // ]);
        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];
        $this->validate($request, $rules);
        event(new Registered($user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ])));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());

        // dd($request->all());
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function registered(Request $request, $user)
    {
        return response([
            'message' => "Success",
            'status' => 1
        ]);
    }
}