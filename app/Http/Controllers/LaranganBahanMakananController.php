<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use App\Models\LaranganBahanMakanan;
use Illuminate\Support\Facades\DB;

class LaranganBahanMakananController extends Controller
{
    public function index(){
        $larangans = DB::table('larangan_bahan_makanans')
        ->join('bahan_makanans', 'larangan_bahan_makanans.id_bahan_makanan', '=', 'bahan_makanans.id')
        ->join('penyakits', 'larangan_bahan_makanans.id_penyakit', '=', 'penyakits.id')
        ->select(
            'larangan_bahan_makanans.id as id_larangan_bahan_makanan',
            'larangan_bahan_makanans.id_bahan_makanan as id_bahan_makanan',
            'larangan_bahan_makanans.id_penyakit as id_penyakit',
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
        if(count($larangans) > 0){
            return new TAResource(true, 'List Data Larangan Bahan Makanan',
            $larangans); // return data semua larangan bahan makanan dalam bentuk json
        }

        return response([
            'message' => 'Data Larangan Bahan Makanan Tidak Ditemukan',
            'data' => null
        ], 400); // return message data larangan bahan makanan kosong
    }

    public function deleteLBahanMakanan($id)
    {
        $larangan = LaranganBahanMakanan::find($id);

        if(is_null($larangan)){
            return response([
                'message' => 'Larangan Bahan Makanan Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($larangan->delete()){
            return response([
                'message' =>'Delete Larangan Bahan Makanan Sukses',
                'data' => [$larangan]
            ], 200);
        }
        return response([
            'message' => 'Delete Larangan Bahan Makanan Gagal',
            'data' => null
        ], 400);
    }
}
