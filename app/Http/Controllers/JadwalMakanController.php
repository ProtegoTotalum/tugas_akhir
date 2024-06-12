<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalMakan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;

class JadwalMakanController extends Controller
{
    public function index()
    {
        //get jadwal
        $jadwal =  JadwalMakan::with(['user'])->get();
        //render view with posts
        if(count($jadwal) > 0){
            return new TAResource(true, 'List Data Jadwal',
            $jadwal); // return data semua jadwal dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data jadwal kosong
    }

    public function storeJadwalMakan(Request $request){
        // Definisikan aturan validasi
        $rules = [
            'tipe_jadwal_makan' => 'required',
            'pengulangan_jadwal_makan' => 'required',
            'waktu_makan' => 'required',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'tipe_jadwal_makan.required' => 'Tipe jadwal wajib diisi',
            'pengulangan_jadwal_makan.required' => 'Pengulangan waktu makan wajib diisi.',
            'waktu_makan.required' => 'Waktu tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $jadwal = JadwalMakan::create([ 
            'id_user' => $request->id_user,
            'status_jadwal' => $request->status_jadwal,
            'tipe_jadwal_makan' => $request->tipe_jadwal_makan,
            'pengulangan_jadwal_makan' => $request->pengulangan_jadwal_makan,
            'waktu_makan' => $request->waktu_makan,
            'senin' => $request->senin,
            'selasa' => $request->selasa,
            'rabu' => $request->rabu,
            'kamis' => $request->kamis,
            'jumat' => $request->jumat,
            'sabtu' => $request->sabtu,
            'minggu' => $request->minggu,
        ]);

        return new TAResource(true, 'Data Jadwal Makan Berhasil Ditambahkan!', [$jadwal]);
    }

    public function delete($id)
    {
        $jadwal = JadwalMakan::find($id);

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($jadwal->delete()){
            return response([
                'message' =>'Delete Jadwal Sukses',
                'data' => [$jadwal]
            ], 200);
        }
        return response([
            'message' => 'Delete Jadwal Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $jadwal = JadwalMakan::find($id);

        if(!is_null($jadwal)){
            return response([
                'message' => 'Data Jadwal Ditemukan',
                'data' => [$jadwal]
            ], 200);
        }

        return response([
            'message' => 'Data Jadwal Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data jadwal tidak ditemukan
    }

    public function update(Request $request, $id)
    {
         // Definisikan aturan validasi
         $rules = [
            'tipe_jadwal_makan' => 'required',
            'pengulangan_jadwal_makan' => 'required',
            'waktu_makan' => 'required',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'tipe_jadwal_makan.required' => 'Tipe jadwal wajib diisi',
            'pengulangan_jadwal_makan.required' => 'Pengulangan waktu makan wajib diisi.',
            'waktu_makan.required' => 'Waktu tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $jadwal = JadwalMakan::find($id);
        $jadwal->update([
            'status_jadwal' => $request->status_jadwal,
            'tipe_jadwal_makan' => $request->tipe_jadwal_makan,
            'pengulangan_jadwal_makan' => $request->pengulangan_jadwal_makan,
            'waktu_makan' => $request->waktu_makan,
            'senin' => $request->senin,
            'selasa' => $request->selasa,
            'rabu' => $request->rabu,
            'kamis' => $request->kamis,
            'jumat' => $request->jumat,
            'sabtu' => $request->sabtu,
            'minggu' => $request->minggu,
        ]);
        // alihkan halaman ke halaman jadwal
        return new TAResource(true, 'Data Jadwal Berhasil Diupdate!', [$jadwal]);
    }

    public function getDataJadwalMakanUser($id_user){
        $jadwal = JadwalMakan::where('id_user', $id_user)
            ->orderBy('waktu_makan', 'asc')
            ->get();

        if(!is_null($jadwal)){
            return response([
                'message' => 'Data Jadwal Ditemukan',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Data Jadwal Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data jadwal tidak ditemukan
    }

    public function updateStatusJadwal($id_jadwal, $new_status){
        $jadwal = JadwalMakan::find($id_jadwal);
        $status = null;

        if(!is_null($jadwal)){
            $jadwal->update([
                'status_jadwal' => $new_status,
            ]);
            if($new_status == 1){
                $status = "Pengingat Dinyalakan";
            }else{
                $status = "Pengingat Dimatikan";
            }
            return response([
                'success' => 'true', 
                'message' => $status,
            ], 200);
        }

        return response([
            'message' => 'Data Jadwal Tidak Ditemukan',
        ], 404); // return message saat data jadwal tidak ditemukan
    }
}
