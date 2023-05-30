<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;


class UserController extends Controller
{

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[a-zA-Z\d\W_]{8,}$/',
        ]);



        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }


    public function update(Request $request, User $user)
    {
        if (!$request->user()->is_admin AND $request->user()->id != $user->id) {return response()->json(['message' => 'Unauthorized'], 401);}

        $validator = Validator::make($request->all(),[
            'name' => 'string|max:255|required',
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user), 'required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user->email = $request->email;
        $user->name = $request->name;

        if ($request->user()->is_admin AND isset($request->is_admin)){
            $user->is_admin = $request->is_admin;
        }

        $user->save();

        return response()->json(['User updated successfully.', $user]);
    }


    public function login(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){

            return Response(['message' => $validator->errors()],401);
        }

        if(Auth::attempt($request->all())){

            $user = Auth::user();

            $success =  $user->createToken('MyApp')->plainTextToken;

            return Response(['token' => $success],200);
        }

        return Response(['message' => 'email or password wrong'],401);
    }


    public function logout(): Response
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return Response(['data' => 'User Logout successfully.'],200);
    }



    public function select(): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }

        return Response(['data' => 'Unauthorized'],401);
    }

}
