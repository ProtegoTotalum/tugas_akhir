<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TAResource;
use App\Models\RekomendasiBahanMakanan;
use Illuminate\Support\Facades\DB;

class RekomendasiBahanMakananController extends Controller
{
    public function index(){
        $rekomendasis = DB::table('rekomendasi_bahan_makanans')
        ->join('bahan_makanans', 'rekomendasi_bahan_makanans.id_bahan_makanan', '=', 'bahan_makanans.id')
        ->join('penyakits', 'rekomendasi_bahan_makanans.id_penyakit', '=', 'penyakits.id')
        ->select(
            'rekomendasi_bahan_makanans.id as id_rekomendasi_bahan_makanan',
            'rekomendasi_bahan_makanans.id_bahan_makanan as id_bahan_makanan',
            'rekomendasi_bahan_makanans.id_penyakit as id_penyakit',
            'penyakits.nama_penyakit as nama_penyakit',
            'bahan_makanans.nama_bahan_makanan as nama_bahan_makanan',
            'bahan_makanans.takaran(g) as takaran(g)',
            'bahan_makanans.kalori as kalori',
            'bahan_makanans.karbohidrat as karbohidrat',
            'bahan_makanans.protein_nabati as protein_nabati',
            'bahan_makanans.protein_hewani as protein_hewani',
            'bahan_makanans.lemak as lemak',
        )
        ->get();
        if(count($rekomendasis) > 0){
            return new TAResource(true, 'List Data Rekomendasi Bahan Makanan',
            $rekomendasis); // return data semua rekomendasi bahan makanan dalam bentuk json
        }

        return response([
            'message' => 'Data Rekomendasi Bahan Makanan Tidak Ditemukan',
            'data' => null
        ], 400); // return message data rekomendasi bahan makanan kosong
    }

    public function deleteRBahanMakanan($id)
    {
        $rekomendasi = RekomendasiBahanMakanan::find($id);

        if(is_null($rekomendasi)){
            return response([
                'message' => 'Rekomendasi Bahan Makanan Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($rekomendasi->delete()){
            return response([
                'message' =>'Delete Rekomendasi Bahan Makanan Sukses',
                'data' => [$rekomendasi]
            ], 200);
        }
        return response([
            'message' => 'Delete Rekomendasi Bahan Makanan Gagal',
            'data' => null
        ], 400);
    }
}
