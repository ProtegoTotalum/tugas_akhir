<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\Penyakit;
use App\Models\RekomendasiObat;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use Illuminate\Support\Facades\DB;

class RekomendasiObatController extends Controller
{
    public function getDataRObat($id_analisa){
        $robat = DB::table('rekomendasi_obats')
        ->join('obats', 'rekomendasi_obats.id_obat', '=', 'obats.id')
        ->join('analisa_dokters', 'rekomendasi_obats.id_analisa', '=', 'analisa_dokters.id')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->select(
            'rekomendasi_obats.id as id_rekomendasi_obat',
            'rekomendasi_obats.id_obat as id_obat',
            'rekomendasi_obats.id_analisa as id_analisa',
            'diagnosas.id as id_diagnosa',
            'obats.nama_obat as nama_obat',
            'rekomendasi_obats.aturan_minum_obat as aturan_minum_obat',
            'rekomendasi_obats.catatan_minum_obat as catatan_minum_obat',
            'obats.jenis_obat as jenis_obat',
            'obats.kegunaan_obat as kegunaan_obat',
            'obats.harga_obat as harga_obat'
        )
        ->where('rekomendasi_obats.id_analisa',$id_analisa)
        ->get();

        if(count($robat) > 0){
            return new TAResource(true, 'List Data Rekomendasi Obat',
            $robat); // return data semua rekomendasi obat dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data rekomendasi obat kosong
    }

    public function getDataRObatByDiagnosa($id_diagnosa){
        $robat = DB::table('rekomendasi_obats')
        ->join('obats', 'rekomendasi_obats.id_obat', '=', 'obats.id')
        ->join('analisa_dokters', 'rekomendasi_obats.id_analisa', '=', 'analisa_dokters.id')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->select(
            'rekomendasi_obats.id as id_rekomendasi_obat',
            'rekomendasi_obats.id_obat as id_obat',
            'rekomendasi_obats.id_analisa as id_analisa',
            'diagnosas.id as id_diagnosa',
            'obats.nama_obat as nama_obat',
            'rekomendasi_obats.aturan_minum_obat as aturan_minum_obat',
            'rekomendasi_obats.catatan_minum_obat as catatan_minum_obat',
            'obats.jenis_obat as jenis_obat',
            'obats.kegunaan_obat as kegunaan_obat',
            'obats.harga_obat as harga_obat'
        )
        ->where('analisa_dokters.id_diagnosa',$id_diagnosa)
        ->get();

        if(count($robat) > 0){
            return new TAResource(true, 'List Data Rekomendasi Obat',
            $robat); // return data semua rekomendasi obat dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data rekomendasi obat kosong
    }

    public function storeRObat(Request $request, $id_analisa){
        // Definisikan aturan validasi
        $rules = [
            'id_obat' => 'required',
            'aturan_minum_obat' => 'required',
            'catatan_minum_obat' => 'required',
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'id_obat.required' => 'Wajib memilih salah satu obat untuk ditambahkan',
            'aturan_minum_obat.required' => 'Aturan minum obat wajib diisi.',
            'catatan_minum_obat.required' => 'Catatan aturan minum obat wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $robat = RekomendasiObat::create([
            'id_obat' => $request->id_obat,
            'id_analisa' => $id_analisa,
            'aturan_minum_obat' => $request->aturan_minum_obat,
            'catatan_minum_obat' => $request->catatan_minum_obat,
        ]);

        return new TAResource(true, 'Obat Berhasil Ditambahkan!', [$robat]);
    }

    public function deleteRObat($id)
    {
        $robat = RekomendasiObat::find($id);

        if(is_null($robat)){
            return response([
                'message' => 'Rekomendasi Obat Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($robat->delete()){
            return response([
                'message' =>'Delete Rekomendasi Obat Sukses',
                'data' => [$robat]
            ], 200);
        }
        return response([
            'message' => 'Delete Rekomendasi Obat Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $robat = DB::table('rekomendasi_obats')
        ->join('obats', 'rekomendasi_obats.id_obat', '=', 'obats.id')
        ->join('analisa_dokters', 'rekomendasi_obats.id_analisa', '=', 'analisa_dokters.id')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->select(
            'rekomendasi_obats.id as id_rekomendasi_obat',
            'rekomendasi_obats.id_obat as id_obat',
            'rekomendasi_obats.id_analisa as id_analisa',
            'diagnosas.id as id_diagnosa',
            'obats.nama_obat as nama_obat',
            'rekomendasi_obats.aturan_minum_obat as aturan_minum_obat',
            'rekomendasi_obats.catatan_minum_obat as catatan_minum_obat',
            'obats.jenis_obat as jenis_obat',
            'obats.kegunaan_obat as kegunaan_obat',
            'obats.harga_obat as harga_obat'
        )
        ->where('rekomendasi_obats.id',$id)
        ->first();

        if(!is_null($robat)){
            return response([
                'message' => 'Data Rekomendasi Obat Ditemukan',
                'data' => [$robat]
            ], 200);
        }

        return response([
            'message' => 'Data Rekomendasi Obat Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data rekomendasi obat tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'aturan_minum_obat' => 'required',
            'catatan_minum_obat' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $robat = RekomendasiObat::find($id);
        $robat->update([
            'aturan_minum_obat' => $request->aturan_minum_obat,
            'catatan_minum_obat' => $request->catatan_minum_obat,
        ]);
        
        return new TAResource(true, 'Data Rekomendasi Obat Berhasil Diupdate!', [$robat]);
    }



    // public function store(Request $request){
    //     // Definisikan aturan validasi
    //     $rules = [
    //         'nama_obat' => 'required',
    //         'jenis_obat' => 'required',
    //         'kegunaan_obat' => 'required',
    //         'aturan_minum_obat' => 'required',
    //         'harga_obat' => 'required',
    //         'penyakit' => 'required|array|min:1',  // Validasi bahwa setidaknya satu checkbox penyakit harus dicentang
    //         'penyakit.*' => 'in:1,2,3,4,5'
    //     ];

    //     // Definisikan pesan kesalahan kustom
    //     $messages = [
    //         'nama_obat.required' => 'Nama obat wajib diisi.',
    //         'jenis_obat.required' => 'Jenis obat wajib diisi.',
    //         'kegunaan_obat.required' => 'Kegunaan obat wajib diisi.',
    //         'aturan_minum_obat.required' => 'Aturan minum obat wajib diisi.',
    //         'harga_obat.required' => 'Harga obat wajib diisi.',
    //         'penyakit.min' => 'Setidaknya satu penyakit harus dipilih.',
    //         'penyakit.*.in' => 'Penyakit yang dipilih tidak valid.'
    //     ];

    //     $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     $obat = Obat::create([
    //         'nama_obat' => $request->nama_obat,
    //         'jenis_obat' => $request->jenis_obat,
    //         'kegunaan_obat' => $request->kegunaan_obat,
    //         'aturan_minum_obat' => $request->aturan_minum_obat,
    //         'harga_obat' => $request->harga_obat, 
    //     ]);

    //     foreach ($request->penyakit as $penyakit_id) {
    //         $robat = RekomendasiObat::create([
    //             'id_obat' => $obat->id,
    //             'id_penyakit' => $penyakit_id,
    //         ]);
    //     }

    //     return new TAResource(true, 'Data Obat Berhasil Ditambahkan!', $robat);


    // } 
    
}
