<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function createUser(Request $request)
    {

        $request->validate([
            'name'     => 'required',
            'email'    => ['required', 'email', 'unique:users'],
            'password' => 'required'
        ]);
        // return $this->returnTokenJson($request);

        //   return response()->json($request, 200);


        $user = new User([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        $user->save();

        // return response()->json($request, 200);
        return $this->returnTokenJson($request);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => 'required'
        ]);

        return $this->returnTokenJson($request);

    }


    public function returnTokenJson(Request $request)
    {

        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if ( ! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json([
            'token' => $token
        ], 200);

    }

    public function refreshToken()
    {
        // return response()->json([ 'token' =>'OK'], 200);
        // здесь проверяется сам токен переданный в теле или в гете и если проверка проходит,
        // то пост сохраняется
        if ( ! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'user not found'], 404);
        } else {
            $token    = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);

            return response()->json(['token' => $newToken], 200);
        }

    }

}
