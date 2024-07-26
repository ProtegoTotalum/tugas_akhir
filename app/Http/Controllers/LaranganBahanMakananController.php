<?php

namespace App\Http\Controllers;

use App\Http\Requests\LBahanMakananImportRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use App\Imports\LBahanMakananImport;
use App\Models\LaranganBahanMakanan;
use App\Models\RekomendasiBahanMakanan;
use App\Models\Penyakit;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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

    public function getDataLByPenyakit($id_penyakit){
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
        ->where('larangan_bahan_makanans.id_penyakit',$id_penyakit)
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id_bahan_makanan' => 'required',
            'id_penyakit' => 'required'
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $id_bahan_makanan = $request->id_bahan_makanan;
        $id_penyakit = $request->id_penyakit;

        $cekRekom = RekomendasiBahanMakanan::where('id_bahan_makanan', $id_bahan_makanan)
        ->where('id_penyakit', $id_penyakit)
        ->first();

        $cekLarangan = LaranganBahanMakanan::where('id_bahan_makanan', $id_bahan_makanan)
        ->where('id_penyakit', $id_penyakit)
        ->first();

        $penyakit = Penyakit::where('id',$id_penyakit)
        ->first();

        $nama_penyakit = $penyakit->nama_penyakit;

        if(is_null($cekRekom) && is_null($cekLarangan)){
            $larangan = LaranganBahanMakanan::create([ 
                'id_bahan_makanan' => $id_bahan_makanan,
                'id_penyakit' => $id_penyakit
            ]);
    
            return new TAResource(true, 'Data Larangan Bahan Makanan Berhasil Ditambahkan!', [$larangan]);
        }else{
            if($cekRekom){
                return response([
                    'message' => 'Bahan Makanan Dengan Penyakit '.$nama_penyakit.' Telah Ditambahkan Di Bagian Rekomendasi',
                    'data' => null
                ], 404);
            }else{
                return response([
                    'message' => 'Bahan Makanan Dengan Penyakit '.$nama_penyakit.' Telah Ditambahkan Di Bagian Larangan',
                    'data' => null
                ], 404);
            }
        }
    }

    public function updateTipeLarangan($id_larangan_bahan_makanan){
        $larangan = RekomendasiBahanMakanan::find($id_larangan_bahan_makanan);

        $id_bahan_makanan = $larangan->id_bahan_makanan;
        $id_penyakit = $larangan->id_penyakit;

        $rekomendasi = RekomendasiBahanMakanan::create([
            'id_bahan_makanan' => $id_bahan_makanan,
            'id_penyakit' => $id_penyakit,
        ]);

        if($larangan->delete()){
            return new TAResource(true, 'Data Bahan Makanan Berhasil Diubah Ke Larangan!', [$rekomendasi]);
        }else{
            return response([
                'message' => 'Pindah ke Rekomendasi Bahan Makanan Gagal',
                'data' => null
            ], 400);
        }
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

    public function importLarangan(LBahanMakananImportRequest $request)
    {
        // Log file information
        Log::info('File uploaded', ['file' => $request->file('file')->getClientOriginalName()]);
                
        // Ensure the file is uploaded
        if (!$request->hasFile('file')) {
            Log::error('No file uploaded');
            return redirect()->back()->with('error', 'No file uploaded');
        }

        // Ensure the file is valid
        $file = $request->file('file');
        if (!$file->isValid()) {
            Log::error('Uploaded file is not valid');
            return redirect()->back()->with('error', 'Uploaded file is not valid');
        }

        try {
            Excel::import(new LBahanMakananImport, $file);
            return redirect()->back()->with('success', 'Data larangan makanan berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Error during import', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }
}
