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
    public function index(){
        $robat = DB::table('rekomendasi_obats')
        ->join('obats', 'rekomendasi_obats.id_obat', '=', 'obats.id')
        ->join('penyakits', 'rekomendasi_obats.id_penyakit', '=', 'penyakits.id')
        ->select(
            'rekomendasi_obats.id as id_rekomendasi_obat',
            'obats.nama_obat as nama_obat',
            'penyakits.nama_penyakit as nama_penyakit',
            'obats.jenis_obat as jenis_obat',
            'obats.kegunaan_obat as kegunaan_obat',
            'obats.aturan_minum_obat as aturan_minum_obat',
            'obats.harga_obat as harga_obat'
        )
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

    public function store(Request $request){
        // Definisikan aturan validasi
        $rules = [
            'nama_obat' => 'required',
            'jenis_obat' => 'required',
            'kegunaan_obat' => 'required',
            'aturan_minum_obat' => 'required',
            'harga_obat' => 'required',
            'penyakit' => 'required|array|min:1',  // Validasi bahwa setidaknya satu checkbox penyakit harus dicentang
            'penyakit.*' => 'in:1,2,3,4,5'
        ];

        // Definisikan pesan kesalahan kustom
        $messages = [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'jenis_obat.required' => 'Jenis obat wajib diisi.',
            'kegunaan_obat.required' => 'Kegunaan obat wajib diisi.',
            'aturan_minum_obat.required' => 'Aturan minum obat wajib diisi.',
            'harga_obat.required' => 'Harga obat wajib diisi.',
            'penyakit.min' => 'Setidaknya satu penyakit harus dipilih.',
            'penyakit.*.in' => 'Penyakit yang dipilih tidak valid.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->stopOnFirstFailure(true);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $obat = Obat::create([
            'nama_obat' => $request->nama_obat,
            'jenis_obat' => $request->jenis_obat,
            'kegunaan_obat' => $request->kegunaan_obat,
            'aturan_minum_obat' => $request->aturan_minum_obat,
            'harga_obat' => $request->harga_obat, 
        ]);

        foreach ($request->penyakit as $penyakit_id) {
            $robat = RekomendasiObat::create([
                'id_obat' => $obat->id,
                'id_penyakit' => $penyakit_id,
            ]);
        }

        return new TAResource(true, 'Data Obat Berhasil Ditambahkan!', $robat);


    }
    
}
