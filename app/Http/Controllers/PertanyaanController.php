<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pertanyaan;
use App\Models\Diagnosa;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PertanyaanController extends Controller
{
    public function storePertanyaan(Request $request, $id){
        //cari id user yang sedang login
        $user = User::find($id);
        $id_user = $user->id;

        $rand1 = str_pad(rand(0, pow(10, 3) - 1), 3, '0', STR_PAD_LEFT);
        $rand2 = str_pad(rand(0, pow(10, 3) - 1), 3, '0', STR_PAD_LEFT);
        $rand3 = str_pad(rand(0, pow(10, 3) - 1), 3, '0', STR_PAD_LEFT);

        $count_user = Diagnosa::where('id_user', $id_user)->count();
        $nomor_diagnosa_user = str_pad($count_user + 1, 3, '0', STR_PAD_LEFT);

        $last_diagnosa = Diagnosa::orderBy('id', 'desc')->first();
        $id_diagnosa = $last_diagnosa ? str_pad($last_diagnosa->id + 1, 3, '0', STR_PAD_LEFT) : '001';

        $tgl_skrg = Carbon::now()->format('Ymd');

        $nomor_diagnosa = "{$rand1}{$id_user}{$nomor_diagnosa_user}.{$rand2}{$id_diagnosa}{$tgl_skrg}{$rand3}";


        $creatediagnosa = Diagnosa::create([
            'id_user' => $id_user,
            'nomor_diagnosa_user' => $nomor_diagnosa_user,
            'nomor_diagnosa' => $nomor_diagnosa, 
        ]);

        $diagnosa = DB::table('diagnosas')
        ->select('diagnosas.id as id_diagnosa')
        ->where('diagnosas.id_user',$id_user)
        ->where('diagnosas.nomor_diagnosa_user',$nomor_diagnosa_user)
        ->where('diagnosas.nomor_diagnosa',$nomor_diagnosa)
        ->first();

        $id_diagnosa = $diagnosa->id_diagnosa;

        // return response()->json([
        //     'id_diagnosa' =>$id_diagnosa,
        // ], 201);

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.id_gejala' => 'required|exists:gejalas,id',
            'jawaban.*.jawaban_user' => 'required|string',
        ]);


        foreach ($request->input('jawaban') as $jawaban) {
            Pertanyaan::create([
                'id_user' => $id_user,
                'id_gejala' => $jawaban['id_gejala'],
                'nomor_diagnosa_pertanyaan' => $nomor_diagnosa,
                'jawaban_user' => $jawaban['jawaban_user'],
            ]);
        }
        return response()->json([
            'message' => 'Form Diagnosa Berhasil Dibuat dan Jawaban User Berhasil Di Record',
            'id_user' => $id_user,
            'id_diagnosa' =>$id_diagnosa,
            'nomor_diagnosa_pertanyan' => $nomor_diagnosa,
            'jawaban_user' => $request->input('jawaban')
        ], 201);
    }

    public function showPertanyaan($id_user,$nomor_diagnosa)
    {
        $pertanyaan = DB::table('pertanyaans')
        ->join('users', 'pertanyaans.id_user', '=', 'users.id')
        ->join('gejalas', 'pertanyaans.id_gejala', '=', 'gejalas.id')
        ->select(
            'pertanyaans.id as id_pertanyaan',
            'gejalas.id as id_gejala',
            'pertanyaans.nomor_diagnosa_pertanyaan as nomor_diagnosa_pertanyaan',
            'gejalas.nama_gejala as nama_gejala',
            'pertanyaans.jawaban_user as jawaban_user',
        )
        ->where('pertanyaans.id_user',$id_user)
        ->where('pertanyaans.nomor_diagnosa_pertanyaan',$nomor_diagnosa)
        ->get();
        //render view with posts
        if(count($pertanyaan) > 0){
            // return response([
            //     'message' => 'List Jawaban User',
            //     'data' => [$pertanyaan]
            // ], 200);  // return data semua pertanyaan dalam bentuk json
            return new TAResource(true, 'List Data Pertanyaan',
            $pertanyaan);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data pertanyaan kosong
    }
}
