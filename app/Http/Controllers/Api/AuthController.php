<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all(); //mengambil seluruh data input dan menyimpannya dalam variable registrationData

        $validate = Validator::make($registrationData, [
            'nama_user' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'tgl_lahir_user' => 'required',
            'no_telp_user' => 'required|min:11|max:13',
            'gender_user' => 'required'
        ]); //rule validasi input saat register

        if($validate->fails())
        return response(['message' => $validate->errors()],400);//error validasi input

        $tgl_lahir = $registrationData['tgl_lahir_user'];

        $registrationData['password'] = bcrypt($request->password); //enkripsi password

        //buat id unik
        $rand1 = Str::random(5);

        $format_tgl_lahir = date('ymd', strtotime($tgl_lahir)); //tanggal lahir

        $rand2 = Str::random(5);

        $currentId = User::max('id') + 1;
        $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        $registrationData['id_unique_user'] = $unique_id;

        $user = User::create($registrationData); //membuat user baru
        // $user->id_unique_user = $unique_id;
        // $user->save();
        
        $user->sendEmailVerificationNotification();
        event(new Registered($user));

        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); //return data dalam bentuk json
    }

    // public function register(Request $request){
    //     $validate = Validator::make($request->all(), [
    //         'nama_user' => 'required|max:60',
    //         'email_user' => 'required|email:rfc,dns|unique:users',
    //         'password_user' => 'required|min:8',
    //         'tgl_lahir_user' => 'required',
    //         'no_telp_user' => 'required|min:11|max:13',
    //     ]); //rule validasi input saat register

    //     if($validate->fails())
    //     return response(['message' => $validate->errors()],400);//error validasi input

    //     $tgl_lahir = $request->tgl_lahir_user;

    //     $enkripsipas = bcrypt($request->password); //enkripsi password

    //     //buat id unik
    //     $rand1 = Str::random(5);

    //     $format_tgl_lahir = date('ymd', strtotime($tgl_lahir)); //tanggal lahir

    //     $rand2 = Str::random(5);

    //     $currentId = User::max('id') + 1;
    //     $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

    //     $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;

    //     $user = User::create([ 
    //         'id_unique_user'=> $unique_id,
    //         'nama_user'=> $request->nama_user,
    //         'email_user'=> $request->email_user,
    //         'password_user'=> $enkripsipas,
    //         'tgl_lahir_user'=> $tgl_lahir,
    //         'no_telp_user'=> $request->no_telp_user,
    //     ]);
        
    //     $user->sendEmailVerificationNotification();
    //     event(new Registered($user));

    //     return response([
    //         'message' => 'Register Success',
    //         'user' => $user
    //     ], 200); //return data dalam bentuk json
    // }

    public function login(Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validate->fails())
        return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
        return response(['message' => 'Invalid Credentials'], 401);

        $user = Auth::user();
        if ($user->email_verified_at == NULL) {
            return response([
                'message' => 'Please Verify Your Email'
            ], 401); //Return error jika belum verifikasi email
        }
        $token = $user->createToken('Authentication Token')->accessToken;


        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {        
        // $token = $request->user()->token();
        // $token->revoke();
        // $response = ['message' => 'You have been successfully logged out!'];
        // return response($response, 200);
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User Logged Out Successfully',
        ],200);
    }

    // public function forget(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|unique:users',
    //         'password' => 'required'
    //         ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }
    //     $username = $request->username;
    //     $password = $request->password;
    //     $user = User::where('username', $username)
    //         ->where('password', $password)
    //         ->value('users.id');
        
    //     if(isset($user)){
    //         $user->update([
    //             'password' => $request->password
    //         ]);
    //     }
    // }
}
