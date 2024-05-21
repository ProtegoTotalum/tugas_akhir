<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;

class GejalaController extends Controller
{
    public function index()
    {
        //get gejala
        $gejala = Gejala::latest()->get();
        //render view with posts
        if(count($gejala) > 0){
            return new TAResource(true, 'List Data Gejala',
            $gejala); // return data semua gejala dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data gejala kosong
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_gejala' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $gejala = Gejala::create([ 
            'nama_gejala' => $request->nama_gejala, 
        ]);

        return new TAResource(true, 'Data Gejala Berhasil Ditambahkan!', $gejala);
    }

    public function destroy($id)
    {
        $gejala = Gejala::find($id);

        if(is_null($gejala)){
            return response([
                'message' => 'Gejala Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($gejala->delete()){
            return response([
                'message' =>'Delete Gejala Sukses',
                'data' => $gejala
            ], 200);
        }
        return response([
            'message' => 'Delete Gejala Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $gejala = Gejala::find($id);

        if(!is_null($gejala)){
            return response([
                'message' => 'Data Gejala Ditemukan',
                'data' => $gejala
            ], 200);
        }

        return response([
            'message' => 'Data Gejala Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data gejala tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_gejala' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $gejala = Gejala::find($id);
        $gejala->update([
            'nama_gejala' => $request->nama_gejala, 
        ]);
        // alihkan halaman ke halaman gejala
        return new TAResource(true, 'Data Gejala Berhasil Diupdate!', $gejala);
    }
}
