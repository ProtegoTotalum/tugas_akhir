<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        // $validator = Validator::make($request->all(), [
        //     'nama_user' => 'required',
        //     'tgl_lahir_user' => 'required',
        //     'no_telp_user' => 'required|min:11|max:13',
        //     'gender_user' => 'required',
        //     ]);

        //     if($validator->fails()) {
        //         $errors = $validator->errors();
        //         $errorMessage = '';
        
        //         if($errors->has('nama_user')) {
        //             $errorMessage .= 'Nama Tidak Boleh Kosong';
        //         }else{
        //             if ($errors->has('tgl_lahir_user')) {
        //                 $errorMessage .= 'Tanggal Lahir Tidak Boleh Kosong';
        //             }else{
        //                 if($errors->has('no_telp_user')) {
        //                     if ($errors->first('no_telp_user') === 'The no_telp_user field is required.') {
        //                         $errorMessage .= 'Nomor telepon tidak boleh kosong ';
        //                     } else {
        //                         $errorMessage .= 'Nomor telepon harus diantara 11 dan 13 karakter';
        //                     }
        //                 }else{
        //                     if($errors->has('gender_user')) {
        //                         $errorMessage .= 'Gender tidak boleh kosong ';                                
        //                     }
        //                 }
        //             }
        //             return response(['message' => $errorMessage], 400);
        //         }
        //     }

        // Definisikan aturan validasi
        $rules = [
            'nama_user' => 'required',
            'tgl_lahir_user' => 'required',
            'no_telp_user' => 'required|min:11|max:13',
            'gender_user' => 'required',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'nama_user.required' => 'Nama wajib diisi.',
            'tgl_lahir_user.required' => 'Tanggal lahir wajib diisi.',
            'no_telp_user.min' => 'Nomor telepon harus minimal 11 karakter.',
            'no_telp_user.max' => 'Nomor telepon harus minimal 13 karakter.',
            'gender_user.required' => 'Jenis Kelamin wajib diisi.',
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
            'no_telp_user' => $request->no_telp_user,
            'gender_user' => $request->gender_user,
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
}
