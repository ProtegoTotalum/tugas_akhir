<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password; 
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\ApiResetPassword;
use Carbon\Carbon;

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
            'bb_user' => 'required',
            'tinggi_user' => 'required',
            'gender_user' => 'required',
            'alamat_user' => 'required',
            'kota_user' => 'required',
            'provinsi_user' => 'required'
        ]); //rule validasi input saat register

        if($validate->fails()) {
            $errors = $validate->errors();
            $errorMessage = '';
    
            if($errors->has('email')) {
                if ($errors->first('email') === 'The email field is required.') {
                    $errorMessage .= 'Email Tidak Boleh Kosong';
                } else {
                    $errorMessage .= 'Gunakan alamat email yang valid';
                }
            }else{
                if($errors->has('no_telp_user')) {
                    if ($errors->first('no_telp_user') === 'The no_telp_user field is required.') {
                        $errorMessage .= 'Nomor telepon tidak boleh kosong ';
                    } else {
                        $errorMessage .= 'Nomor telepon harus diantara 11 dan 13 karakter';
                    }
                }else{
                    if($errors->has('password')) {
                        if ($errors->first('password') === 'The password field is required.') {
                            $errorMessage .= 'Password tidak boleh kosong ';
                        } else {
                            $errorMessage .= 'Password minimal 8 karakter ';
                        }
                    }
                }
                return response(['message' => $errorMessage], 400);
            }
        }
        // if ($validate->fails()) {
        //     return response()->json(['errors' => $validate->errors()], 422);
        // }

        $tgl_lahir = $registrationData['tgl_lahir_user'];
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        // Hitung umur berdasarkan tanggal lahir
        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        // Format umur sebagai "xx tahun xx bulan"
        $umur = $years . ' tahun ' . $months . ' bulan';
        $registrationData['umur_user'] = $umur;  
        // Input role otomatis
        $role_user = "user";
        $registrationData['role_user'] = $role_user;

        $registrationData['password'] = bcrypt($request->password); //enkripsi password

        $user = User::create($registrationData); //membuat user baru
        
        $user->sendEmailVerificationNotification();
        event(new Registered($user));

        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200); //return data dalam bentuk json

        // //buat id unik
        // $rand1 = Str::random(7);

        // $format_tgl_lahir = date('ymd', strtotime($tgl_lahir)); //tanggal lahir

        // $rand2 = Str::random(5);

        // $currentId = User::max('id') + 1;
        // $padId = str_pad($currentId, 3, '0', STR_PAD_LEFT); //id user saat ini

        // $unique_id = $rand1 . $format_tgl_lahir . $rand2 . $padId;
        // $registrationData['id_unique_user'] = $unique_id;


        // $user = User::create($registrationData); //membuat user baru
        // $user->id_unique_user = $unique_id;
        // $user->save();
        
        // $user->sendEmailVerificationNotification();
        // event(new Registered($user));

        // return response([
        //     'message' => 'Register Success',
        //     'user' => $user
        // ], 200); //return data dalam bentuk json
    }
    
    public function login(Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if($validate->fails()) {
            $errors = $validate->errors();
            $errorMessage = '';
    
            if($errors->has('email')) {
                if ($errors->first('email') === 'The email field is required.') {
                    $errorMessage .= 'Email tidak boleh kosong';
                } else {
                    $errorMessage .= 'Gunakan alamat email yang valid';
                }
            }
    
            if($errors->has('password')) {
                $errorMessage .= 'Password tidak boleh kosong';
            }
    
            return response(['message' => $errorMessage], 400);
        }

        // if($validate->fails())
        // return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
        return response(['message' => 'Email dan password tidak valid'], 401);

        $user = Auth::user();
        if ($user->email_verified_at == NULL) {
            return response([
                'message' => 'Verifikasi email anda terlebih dahulu'
            ], 401); //Return error jika belum verifikasi email
        }
        // $token = $user->createToken('Authentication Token')->accessToken;


        // return response([
        //     'message' => 'Authenticated',
        //     'user' => $user,
        //     'token_type' => 'Bearer',
        //     'access_token' => $token
        // ]);

        return response([
            'message' => 'Authenticated',
            'user' => $user,
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $token = Password::createToken($user);

        $user->notify(new ApiResetPassword($token));

        return response()->json(['message' => 'Password reset link sent.']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }
    
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
    
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->setRememberToken(Str::random(60));
            $user->save();
    
            event(new PasswordReset($user));
        });
    
        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password has been reset.']);
        } else {
            return response()->json(['message' => 'Failed to reset password.'], 500);
        }
    }

}
// public function logout(Request $request)
    // {        
    //     // $token = $request->user()->token();
    //     // $token->revoke();
    //     // $response = ['message' => 'You have been successfully logged out!'];
    //     // return response($response, 200);
    //     auth()->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User Logged Out Successfully',
    //     ],200);
    // }

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
