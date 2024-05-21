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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'status_jadwal' => 'required',
            'tipe_jadwal_makan' => 'required',
            'pengulangan_jadwal_makan' => 'required',
            'waktu_makan' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        // $senin = $request->senin;
        // $selasa = $request->selasa;
        // $rabu = $request->rabu;
        // $kamis = $request->kamis;
        // $jumat = $request->jumat;
        // $sabtu = $request->sabtu;
        // $minggu = $request->minggu;

        // if($senin == 1 && $selasa == 1 && $rabu == 1 && $kamis == 1 && $jumat == 1 && $sabtu == 1 && $minggu == 1){
        //     $pengulangan_jadwal_makan = "Setiap Hari";
        // }else{

        // }

        $jadwal = JadwalMakan::create([ 
            'id_user' => $request->id_user,
            'status_jadwal_makan' => $request->status_jadwal_makan,
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

        return new TAResource(true, 'Data Jadwal Makan Berhasil Ditambahkan!', $jadwal);
    }

    public function destroy($id)
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
                'data' => $jadwal
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
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Data Jadwal Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data jadwal tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'status_jadwal' => 'required',
            'tipe_jadwal_makan' => 'required',
            'pengulangan_jadwal_makan' => 'required',
            'waktu_makan' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jadwal = JadwalMakan::find($id);
        $jadwal->update([
            'id_user' => $request->id_user,
            'status_jadwal_makan' => $request->status_jadwal_makan,
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
        return new TAResource(true, 'Data Jadwal Berhasil Diupdate!', $jadwal);
    }
}
