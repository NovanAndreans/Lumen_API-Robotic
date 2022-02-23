<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
  {
      $this->validate($request, [
          'email' => 'required|email',
        //   'password' => 'required|min:6'
        'password' => 'required'
      ]);

      $email = $request->input('email');
      $password = $request->input('password');

      $user = User::where('user_email', $email)->first();
      if (!$user) {
          return response()->json(['message' => 'Login failed'], 401);
      }

      $isValidPassword = Hash::check($password, $user->user_password);
      if (!$isValidPassword) {
        return response()->json(['message' => 'Login failed'], 401);
      }

      return response()->json($user);
  }
}