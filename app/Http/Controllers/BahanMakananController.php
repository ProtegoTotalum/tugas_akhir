<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanMakanan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use App\Http\Requests\BahanMakananImportRequest;
use App\Imports\BahanMakananImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BahanMakananController extends Controller
{
    public function index()
    {
        //get bahan
        $bahan = BahanMakanan::latest()->get();
        //render view with posts
        if(count($bahan) > 0){
            return new TAResource(true, 'List Data Bahan Makanan',
            $bahan); // return data semua bahan dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data bahan kosong
    }

    public function showByGolongan($golongan)
    {
        //get bahan
        // $bahan = BahanMakanan::latest()->get();
        //render view with posts
        $bahan = DB::table('bahan_makanans')
        ->select(
            'bahan_makanans.id as id',
            'bahan_makanans.nama_bahan_makanan as nama_bahan_makanan',
            'bahan_makanans.golongan_bahan_makanan as golongan_bahan_makanan',
            'bahan_makanans.takaran(g) as takaran(g)',
            'bahan_makanans.kalori as kalori',
            'bahan_makanans.karbohidrat as karbohidrat',
            'bahan_makanans.protein_nabati as protein_nabati',
            'bahan_makanans.protein_hewani as protein_hewani',
            'bahan_makanans.lemak as lemak',
        )
        ->where('bahan_makanans.golongan_bahan_makanan',$golongan)
        ->get();
        if(count($bahan) > 0){
            return new TAResource(true, 'List Data Bahan Makanan',
            $bahan); // return data semua bahan dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data bahan kosong
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_bahan_makanan' => 'required',
            'golongan_bahan_makanan' => 'required',
            'takaran' => 'required',
            'kalori' => 'required',
            'karbohidrat' => 'required',
            'protein_nabati' => 'required',
            'protein_hewani' => 'required',
            'lemak' => 'required',
        ]);

        if($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }
        $bahan = BahanMakanan::create([ 
            'nama_bahan_makanan' => $request->nama_bahan_makanan,
            'golongan_bahan_makanan' => $request->golongan_bahan_makanan,
            'takaran(g)' => $request->takaran,
            'kalori' => $request->kalori,
            'karbohidrat' => $request->karbohidrat,
            'protein_nabati' => $request->protein_nabati,
            'protein_hewani' => $request->protein_hewani,
            'lemak' => $request->lemak,
        ]);

        return new TAResource(true, 'Data Bahan Makanan Berhasil Ditambahkan!', [$bahan]);
    }

    public function deleteBahanMakanan($id)
    {
        $bahan = BahanMakanan::find($id);

        if(is_null($bahan)){
            return response([
                'message' => 'Bahan Makanan Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($bahan->delete()){
            return response([
                'message' =>'Delete Bahan Makanan Sukses',
                'data' => [$bahan]
            ], 200);
        }
        return response([
            'message' => 'Delete Bahan Makanan Gagal',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $bahan = BahanMakanan::find($id);

        if(!is_null($bahan)){
            return response([
                'message' => 'Data Bahan Makanan Ditemukan',
                'data' => [$bahan]
            ], 200);
        }

        return response([
            'message' => 'Data Bahan Makanan Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data bahan tidak ditemukan
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_makanan' => 'required',
            'golongan_bahan_makanan' => 'required',
            'takaran' => 'required',
            'kalori' => 'required',
            'karbohidrat' => 'required',
            'protein_nabati' => 'required',
            'protein_hewani' => 'required',
            'lemak' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $bahan = BahanMakanan::find($id);
        $bahan->update([
            'nama_bahan_makanan' => $request->nama_bahan_makanan,
            'golongan_bahan_makanan' => $request->golongan_bahan_makanan,
            'takaran(g)' => $request->takaran,
            'kalori' => $request->kalori,
            'karbohidrat' => $request->karbohidrat,
            'protein_nabati' => $request->protein_nabati,
            'protein_hewani' => $request->protein_hewani,
            'lemak' => $request->lemak,
        ]);
        // alihkan halaman ke halaman bahan
        return new TAResource(true, 'Data Bahan Makanan Berhasil Diupdate!', [$bahan]);
    }

    public function searchBahanMakanan($search)
    {
        $bahan = BahanMakanan::where('nama_bahan_makanan', 'like', "%{$search}%")
                            ->get();

        if(!is_null($bahan)){
            return response([
                'message' => 'Data Bahan Makanan Ditemukan',
                'data' => $bahan
            ], 404);
        }

        return response([
            'message' => 'Data Bahan Makanan Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data bahan tidak ditemukan
    }

    public function import(BahanMakananImportRequest $request)
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
            Excel::import(new BahanMakananImport, $file);
            return redirect()->back()->with('success', 'Data makanan berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Error during import', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }
}
