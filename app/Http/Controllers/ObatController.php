<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;


class ObatController extends Controller
{
    public function index()
    {
        //get obat
        $obat = Obat::latest()->get();
        //render view with posts
        if(count($obat) > 0){
            return new TAResource(true, 'List Data Obat',
            $obat); // return data semua obat dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data obat kosong
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'kegunaan_obat' => 'required',
            'aturan_minum_obat' => 'required',
            'harga_obat' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $obat = Obat::create([ 
            'nama_obat' => $request->nama_obat,
            'jenis_obat' => $request->jenis_obat,
            'kegunaan_obat' => $request->kegunaan_obat,
            'aturan_minum_obat' => $request->aturan_minum_obat,
            'harga_obat' => $request->harga_obat,
        ]);

        return new TAResource(true, 'Data Obat Berhasil Ditambahkan!', $obat);
    }

    public function destroy($id)
    {
        $obat = Obat::find($id);

        if(is_null($obat)){
            return response([
                'message' => 'Obat Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($obat->delete()){
            return response([
                'message' =>'Delete Obat Sukses',
                'data' => $obat
            ], 200);
        }
        return response([
            'message' => 'Delete Obat Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $obat = Obat::find($id);

        if(!is_null($obat)){
            return response([
                'message' => 'Data Obat Ditemukan',
                'data' => $obat
            ], 200);
        }

        return response([
            'message' => 'Data Obat Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data obat tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'kegunaan_obat' => 'required',
            'aturan_minum_obat' => 'required',
            'harga_obat' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $obat = Obat::find($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'jenis_obat' => $request->jenis_obat,
            'kegunaan_obat' => $request->kegunaan_obat,
            'aturan_minum_obat' => $request->aturan_minum_obat,
            'harga_obat' => $request->harga_obat,
        ]);
        // alihkan halaman ke halaman obat
        return new TAResource(true, 'Data Obat Berhasil Diupdate!', $obat);
    }

    public function searchObat($search)
    {
        // $searchTerm = $request->input('search');

        $obat = Obat::where('nama_obat', 'like', "%{$search}%")
                            ->orWhere('jenis_obat', 'like', "%{$search}%")
                            ->get();

        if(!is_null($obat)){
            return response([
                'message' => 'Data Obat Ditemukan',
                'data' => $obat
            ], 200);
        }

        return response([
            'message' => 'Data Obat Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data obat tidak ditemukan
    }

}
