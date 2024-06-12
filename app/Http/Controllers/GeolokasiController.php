<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geolokasi;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;

class GeolokasiController extends Controller
{
    public function index()
    {
        //get geolokasi
        $geolokasi = Geolokasi::latest()->get();
        //render view with posts
        if(count($geolokasi) > 0){
            return new TAResource(true, 'List Data Geolokasi',
            $geolokasi); // return data semua geolokasi dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data geolokasi kosong
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required',
            'jenis_lokasi' => 'required',
            'alamat_lokasi' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $geolokasi = Geolokasi::create([ 
            'nama_lokasi' => $request->nama_lokasi,
            'jenis_lokasi' => $request->jenis_lokasi,
            'alamat_lokasi' => $request->alamat_lokasi, 
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return new TAResource(true, 'Data Geolokasi Berhasil Ditambahkan!', [$geolokasi]);
    }

    public function destroy($id)
    {
        $geolokasi = Geolokasi::find($id);

        if(is_null($geolokasi)){
            return response([
                'message' => 'Geolokasi Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($geolokasi->delete()){
            return response([
                'message' =>'Delete Geolokasi Sukses',
                'data' => [$geolokasi]
            ], 200);
        }
        return response([
            'message' => 'Delete Geolokasi Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $geolokasi = Geolokasi::find($id);

        if(!is_null($geolokasi)){
            return response([
                'message' => 'Data Geolokasi Ditemukan',
                'data' => [$geolokasi]
            ], 200);
        }

        return response([
            'message' => 'Data Geolokasi Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data geolokasi tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required',
            'jenis_lokasi' => 'required',
            'alamat_lokasi' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $geolokasi = Geolokasi::find($id);
        $geolokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'jenis_lokasi' => $request->jenis_lokasi,
            'alamat_lokasi' => $request->alamat_lokasi, 
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);
        // alihkan halaman ke halaman geolokasi
        return new TAResource(true, 'Data Geolokasi Berhasil Diupdate!', [$geolokasi]);
    }
}
