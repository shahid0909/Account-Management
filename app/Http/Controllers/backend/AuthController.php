<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login ()
    {

        return view('backend.auth.login');
    }

    public function loginUser(Request $request)
    {

        try {
            $data = $request->all();

            if (empty($data['email'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please fill email !',
                ], 400);
            } elseif (empty($data['password'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please fill password !',
                ], 400);
            } else {
                $credentials = $request->only('email', 'password');

                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                    $token = $user->createToken('MyApp')->plainTextToken;

//                    if ($user->email_verify == 'Y') {
//                        return response()->json([
//                            'status' => 'success',
//                        ]);
//
//                    } else {
//                        return response()->json([
//                            'status' => 'error',
//                            'message' => 'Account not active !',
//                        ]);
//                    }


                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid credentials !',
                    ]);
                }
            }


        } catch (\Exception $e) {
            // Handle other exceptions if needed
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);

        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
    }
}
