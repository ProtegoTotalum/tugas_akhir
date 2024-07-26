<?php

namespace App\Http\Controllers;

use App\Http\Requests\RBahanMakananImportRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TAResource;
use App\Imports\RBahanMakananImport;
use App\Models\LaranganBahanMakanan;
use Illuminate\Support\Facades\Validator;
use App\Models\RekomendasiBahanMakanan;
use App\Models\Penyakit;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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

    public function getDataRByPenyakit($id_penyakit){
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
        ->where('rekomendasi_bahan_makanans.id_penyakit',$id_penyakit)
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
            $rekomendasi = RekomendasiBahanMakanan::create([ 
                'id_bahan_makanan' => $id_bahan_makanan,
                'id_penyakit' => $id_penyakit
            ]);
    
            return new TAResource(true, 'Data Rekomendasi Bahan Makanan Berhasil Ditambahkan!', [$rekomendasi]);
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

    public function updateTipeRekomendasi($id_rekomendasi_bahan_makanan){
        $rekomendasi = RekomendasiBahanMakanan::find($id_rekomendasi_bahan_makanan);

        $id_bahan_makanan = $rekomendasi->id_bahan_makanan;
        $id_penyakit = $rekomendasi->id_penyakit;

        $larangan = LaranganBahanMakanan::create([
            'id_bahan_makanan' => $id_bahan_makanan,
            'id_penyakit' => $id_penyakit,
        ]);

        if($rekomendasi->delete()){
            return new TAResource(true, 'Data Bahan Makanan Berhasil Diubah Ke Larangan!', [$larangan]);
        }else{
            return response([
                'message' => 'Pindah ke Larangan Bahan Makanan Gagal',
                'data' => null
            ], 400);
        }
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

    public function importRekomendasi(RBahanMakananImportRequest $request)
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
            Excel::import(new RBahanMakananImport, $file);
            return redirect()->back()->with('success', 'Data rekomendasi makanan berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Error during import', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }
}
