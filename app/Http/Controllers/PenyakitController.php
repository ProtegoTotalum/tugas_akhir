<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyakit;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;


class PenyakitController extends Controller
{
    public function index()
    {
        //get penyakit
        $penyakit = Penyakit::latest()->get();
        //render view with posts
        if(count($penyakit) > 0){
            return new TAResource(true, 'List Data Penyakit',
            $penyakit); // return data semua penyakit dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data penyakit kosong
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_penyakit' => 'required',
            'deskripsi_penyakit' => 'required',
            'gejala_penyakit' => 'required',
            'penyebab_penyakit' => 'required',
            'penyebaran_penyakit' => 'required',
            'cara_pencegahan' => 'required',
            'cara_penanganan' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $penyakit = Penyakit::create([ 
            'nama_penyakit' => $request->nama_penyakit,
            'deskripsi_penyakit' => $request->deskripsi_penyakit,
            'gejala_penyakit' => $request->gejala_penyakit,
            'penyebab_penyakit' => $request->penyebab_penyakit,
            'penyebaran_penyakit' => $request->penyebaran_penyakit,
            'cara_pencegahan' => $request->cara_pencegahan,
            'cara_penanganan' => $request->cara_penanganan,
        ]);

        return new TAResource(true, 'Data Penyakit Berhasil Ditambahkan!', $penyakit);
    }

    public function destroy($id)
    {
        $penyakit = Penyakit::find($id);

        if(is_null($penyakit)){
            return response([
                'message' => 'Penyakit Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($penyakit->delete()){
            return response([
                'message' =>'Delete Penyakit Sukses',
                'data' => $penyakit
            ], 200);
        }
        return response([
            'message' => 'Delete Penyakit Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $penyakit = Penyakit::find($id);

        if(!is_null($penyakit)){
            return response([
                'message' => 'Data Penyakit Ditemukan',
                'data' => [$penyakit]
            ], 200);
        }

        return response([
            'message' => 'Data Penyakit Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data penyakit tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi_penyakit' => 'required',
            'gejala_penyakit' => 'required',
            'penyebab_penyakit' => 'required',
            'penyebaran_penyakit' => 'required',
            'cara_pencegahan' => 'required',
            'cara_penanganan' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $penyakit = Penyakit::find($id);
        $penyakit->update([
            'deskripsi_penyakit' => $request->deskripsi_penyakit,
            'gejala_penyakit' => $request->gejala_penyakit,
            'penyebab_penyakit' => $request->penyebab_penyakit,
            'penyebaran_penyakit' => $request->penyebaran_penyakit,
            'cara_pencegahan' => $request->cara_pencegahan,
            'cara_penanganan' => $request->cara_penanganan,
        ]);
        // alihkan halaman ke halaman penyakit
        return new TAResource(true, 'Data Penyakit Berhasil Diupdate!', [$penyakit]);
    }
}
