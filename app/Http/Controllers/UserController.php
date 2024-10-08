<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        //get user
        $user = User::latest()->get();
        //render view with posts
        if(count($user) > 0){
            return new TAResource(true, 'List Data User',
            $user); // return data semua user dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data user kosong
    }

    public function show($id)
    {
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'status' => true,
                'message' => 'Data User Ditemukan',
                'data' => [$user],
            ], 200);
        }

        return response([
            'message' => 'Data User Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data user tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        // Definisikan aturan validasi
        $rules = [
            'nama_user' => 'required',
            'tgl_lahir_user' => 'required',
            'no_telp_user' => 'required|min:11|max:13',
            'gender_user' => 'required',
            'alamat_user' => 'required',
            'kota_user' => 'required',
            'provinsi_user' => 'required',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'nama_user.required' => 'Nama wajib diisi.',
            'tgl_lahir_user.required' => 'Tanggal lahir wajib diisi.',
            'bb_user.required' => 'Berat badan wajib diisi.',
            'tinggi_user.required' => 'Tinggi badan wajib diisi.',
            'no_telp_user.min' => 'Nomor telepon harus minimal 11 karakter.',
            'no_telp_user.max' => 'Nomor telepon harus minimal 13 karakter.',
            'gender_user.required' => 'Jenis kelamin wajib diisi.',
            'alamat_user.required' => 'Alamat wajib diisi.',
            'kota_user.required' => 'Kota atau Kabupaten wajib diisi.',
            'provinsi_user.required' => 'Provinsi wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
            

        $user = User::find($id);
        $tgl_lahir = $request->tgl_lahir_user;
        $formattedBirthdate = Carbon::parse($tgl_lahir);

        $now = Carbon::now();
        $years = $formattedBirthdate->diffInYears($now);
        $months = $formattedBirthdate->copy()->addYears($years)->diffInMonths($now);

        $umur = $years . ' tahun ' . $months . ' bulan';

        $user->update([
            'nama_user' => $request->nama_user,
            'tgl_lahir_user' => $tgl_lahir,
            'umur_user' => $umur,
            'bb_user' => $request->bb_user,
            'tinggi_user' => $request->tinggi_user,
            'no_telp_user' => $request->no_telp_user,
            'gender_user' => $request->gender_user,
            'alamat_user' => $request->alamat_user,
            'kota_user' => $request->kota_user,
            'provinsi_user' => $request->provinsi_user,
        ]);
        
        // alihkan halaman ke halaman user
        return new TAResource(true, 'Data User Berhasil Diupdate!', [$user]);
    }

    public function changeRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role_user' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);
        
        if(!is_null($user)){
            $user->update([
                'role_user' => $request->role_user
            ]);
            return new TAResource(true, 'Data Role User Berhasil Diupdate!', $user);
        }

        return response([
            'message' => 'Data User Tidak Ditemukan',
            'data' => null
        ], 400); // return message data user kosong
    }

    public function changePas(Request $request, $id)
    {
        // Definisikan aturan validasi
        $rules = [
            'password_lama' => 'required',
            'password_baru' => 'required|min:8',
            'password_konfirmasi' => 'required|same:password_baru',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru harus minimal 8 karakter.',
            'password_konfirmasi.required' => 'Konfirmasi password baru wajib diisi.',
            'password_konfirmasi.same' => 'Konfirmasi password baru tidak cocok.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }
        $user = User::find($id);
        
        if (!is_null($user)) {
            if (Hash::check($request->password_lama, $user->password)) {
                // Update password user
                $user->password = Hash::make($request->password_baru);
                $user->save();

                return response()->json([
                    'message' => 'Password berhasil diubah!',
                    'data' => [$user]
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Password lama tidak sesuai',
                ], 400);
            }
        }
        return response([
            'message' => 'Data User Tidak Ditemukan',
            'data' => null
        ], 400); // return message data user kosong
    }

    public function deaktivasiAkun($id)
    {
        $user = User::find($id);
        
        if(!is_null($user)){
            $nama = $user->nama;
            $user->deaktivasi = 1;
            $user->save();
            return response([
                'status' => true,
                'message' => 'Akun user '.$nama.' telah dideaktivasi',
                'data' => [$user],
            ], 200);
        }

        return response([
            'message' => 'Data User Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data user tidak ditemukan
    }

    public function getDataDokter($provinsi_user, $kota_user){

        $dokters = User::where('role_user', 'dokter')
                ->where('provinsi_user', $provinsi_user)
                ->where('kota_user', $kota_user)
                ->get();
        if(count($dokters) > 0){
            return new TAResource(true, 'List Data Dokter',
            $dokters); // return data semua dokter dalam bentuk json
        }

        return response([
            'message' => 'Data Dokter Tidak Ditemukan',
            'data' => null
        ], 400); // return message data dokter kosong
    }

    public function addDokter(Request $request){
        $registrationData = $request->all(); //mengambil seluruh data input dan menyimpannya dalam variable registrationData

        $validate = Validator::make($registrationData, [
            'nama_user' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'tgl_lahir_user' => 'required',
            'no_telp_user' => 'required|min:11|max:13',
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
                } elseif ($errors->first('email') === 'The email has already been taken.') {
                    $errorMessage .= 'Email telah digunakan. Silakan gunakan email lain.';
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
        $role_user = "dokter";
        $registrationData['role_user'] = $role_user;

        $registrationData['email_verified_at'] = $now;

        $registrationData['password'] = bcrypt($request->password); //enkripsi password

        $user = User::create($registrationData); //membuat user baru
        
        return response([
            'message' => 'Berhasil Menambahkan Dokter',
            'user' => $user
        ], 200); //return data dalam bentuk json
    }

    public function updateFcmToken(Request $request)
    {
        $user = User::find($request->id_user);
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response([
            'success' => 'true', 
            'message' => 'Berhasil menambahkan token',
        ], 200);
    }
}
