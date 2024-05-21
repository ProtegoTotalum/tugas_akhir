<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\User;
use App\Models\Diagnosa;
use App\Models\CertaintyFactor;
use App\Models\Penyakit;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
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


        $diagnosa = Diagnosa::create([
            'id_user' => $id_user,
            'nomor_diagnosa_user' => $nomor_diagnosa_user,
            'nomor_diagnosa' => $nomor_diagnosa, 
        ]);

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
            'nomor_diagnosa_user' => $nomor_diagnosa_user,
            'nomor_diagnosa' => $nomor_diagnosa,
            'jawaban' => $request->input('jawaban')
        ], 201);
        
    }

    public function diagnosa($id){
        $diagnosa = DB::table('diagnosas')
        ->join('users', 'diagnosas.id_user', '=', 'users.id')
        ->select(
            'diagnosas.id as id',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'users.nama_user as nama_user',
            'users.tgl_lahir_user as tgl_lahir_user',
            'users.umur_user as umur_user',
            'users.gender_user as gender_user'
        )
        ->where('diagnosas.id_user',$id)
        ->orderBy('diagnosas.created_at', 'desc')
        ->first();

        if($diagnosa){  
            $nomor_diagnosa = $diagnosa->nomor_diagnosa;
            $id_diagnosa = $diagnosa->id;
            $tanggal_diagnosa = $diagnosa->tanggal_diagnosa;
            $nama_user = $diagnosa->nama_user;
            $tgl_lahir_user = $diagnosa->tgl_lahir_user;
            $umur_user = $diagnosa->umur_user;
            $gender_user = $diagnosa->gender_user;
            $cf_user = 0;
            $penyakits = Penyakit::latest()->get();
            $results = [];

            if ($penyakits->isEmpty()) {
                return response()->json(['message' => 'Data Penyakit Tidak Ditemukan'], 422);
            } else {
                foreach ($penyakits as $penyakit) {
                    $id_penyakit = $penyakit->id;
                    $nama_penyakit = $penyakit->nama_penyakit;
            
                    $pertanyaans = Pertanyaan::where('nomor_diagnosa_pertanyaan', $nomor_diagnosa)->get();
                    if ($pertanyaans->isEmpty()) {
                        return response()->json(['message' => 'No Data Pertanyaan Found'], 422);
                    } else {
                        $hasil_cf_komb = 0;
                        $first_iteration = true;
            
                        foreach ($pertanyaans as $pertanyaan) {
                            $id_gejala = $pertanyaan->id_gejala;
                            $jawaban_user = $pertanyaan->jawaban_user;
            
                            if($jawaban_user == "Sangat Yakin"){
                                $cf_user = 1;
                            }else if ($jawaban_user == "Yakin"){
                                $cf_user = 0.8;
                            }else if($jawaban_user == "Cukup Yakin"){
                                $cf_user = 0.6;
                            }else if($jawaban_user == "Sedikit Yakin"){
                                $cf_user = 0.4;
                            }elseif($jawaban_user == "Tidak Yakin"){
                                $cf_user = 0.2;
                            }elseif($jawaban_user == "Tidak Sama Sekali"){
                                $cf_user = 0;
                            }
            
                            if ($cf_user === 0) {
                                continue;
                            }
                            $data_cfs = CertaintyFactor::where('id_penyakit', $id_penyakit)
                                ->where('id_gejala', $id_gejala)
                                ->first(); 
            
                            // Skip gejala yang tidak ada di CertaintyFactor
                            if (is_null($data_cfs)) {
                                continue;
                            }
            
                            $cf_pakar = $data_cfs->certainty_factor;
                            $hasil_cf = $cf_user * $cf_pakar;
            
                            if ($first_iteration) {
                                $hasil_cf_komb = $hasil_cf;
                                $first_iteration = false;
                            } else {
                                $hasil_cf_komb = $hasil_cf_komb + $hasil_cf * (1 - $hasil_cf_komb);
                            }
                        }

                        $hasil_cf_komb_persen = $hasil_cf_komb * 100;
                        $results[] = [
                            'id_penyakit' => $id_penyakit,
                            'nama_penyakit' => $nama_penyakit,
                            'hasil_cf_komb' => $hasil_cf_komb,
                            'hasil_cf_komb_persen' => $hasil_cf_komb_persen
                        ];
                    }
                }
            }
            $max_result = null;
            foreach ($results as $result) {
                if (is_null($max_result) || $result['hasil_cf_komb'] > $max_result['hasil_cf_komb']) {
                    $max_result = $result;
                }
            }
            if (!is_null($max_result)) {
                $hasil_persentase = $max_result['hasil_cf_komb_persen'];
                $hasil_persentase_result = number_format($hasil_persentase,2);
                $id_penyakit_result = $max_result['id_penyakit'];
                $diagnosa_update = Diagnosa::find($id_diagnosa);
                $diagnosa_update->persentase_hasil = $hasil_persentase_result;
                $diagnosa_update->id_penyakit = $id_penyakit_result;
                $diagnosa_update->konfirmasi_dokter = 0;
                $formattedTgl = Carbon::now()->format('Y-m-d');
                $formattedJam = Carbon::now()->format('H:i:s');
                $diagnosa_update->tanggal_diagnosa = $formattedTgl;
                $diagnosa_update->jam_diagnosa = $formattedJam;
                $diagnosa_update->save();
                return response([
                    'message' => 'Data Diagnosa Ditemukan',
                    'data' => [$diagnosa]
                ], 200);
            }
            // return response()->json($results, 200);
            // return response()->json([
            //     'results' => $results,
            //     'max_result' => $max_result
            // ], 200);
        }else{
            return response()->json(['message' => 'No data found'], 422);
        }
    }

    // public function diagnosa($id){

        //     $diagnosa = DB::table('diagnosas')
        //     ->join('users', 'diagnosas.id_user', '=', 'users.id')
        //     ->select(
        //         'diagnosas.nomor_diagnosa as nomor_diagnosa',
        //         'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
        //         'users.nama_user as nama_user',
        //         'users.tgl_lahir_user as tgl_lahir_user',
        //         'users.umur_user as umur_user',
        //         'users.gender_user as gender_user'
        //     )
        //     ->where('diagnosas.id_user',$id)
        //     ->orderBy('diagnosas.created_at', 'desc')
        //     ->first();
    
        //     if($diagnosa){  
        //         $nomor_diagnosa = $diagnosa->nomor_diagnosa;
        //         $tanggal_diagnosa = $diagnosa->tanggal_diagnosa;
        //         $nama_user = $diagnosa->nama_user;
        //         $tgl_lahir_user = $diagnosa->tgl_lahir_user;
        //         $umur_user = $diagnosa->umur_user;
        //         $gender_user = $diagnosa->gender_user;
        //         $cf_user = 0;
        //         // return response()->json([
        //         //     'nomor_diagnosa' => $nomor_diagnosa,
        //         //     'tanggal_diagnosa' => $tanggal_diagnosa,
        //         //     'nama_user' => $nama_user,
        //         //     'tgl_lahir_user' => $tgl_lahir_user,
        //         //     'umur_user' => $umur_user,
        //         //     'gender_user' => $gender_user
        //         // ], 200);
        //         $penyakits = Penyakit::latest()->get();
        //         if($penyakits->isEmpty()){
        //             return response()->json(['message' => 'Data Penyakit Tidak Ditemukan'], 422);
        //         }else{
        //             // return response()->json($penyakits, 200);
        //             foreach($penyakits as $penyakit){
        //                 $id_penyakit = $penyakit->id;
        //                 $nama_penyakit = $penyakit->nama_penyakit;
    
        //                 $pertanyaans = Pertanyaan::where('nomor_diagnosa_pertanyaan', $nomor_diagnosa)->get();
        //                 if ($pertanyaans->isEmpty()) {
        //                     return response()->json(['message' => 'No Data Pertanyaan Found'], 422);
        //                 }else{
        //                     foreach($pertanyaans as $pertanyaan){
        //                         $id_gejala = $pertanyaan->id_gejala;
        //                         $jawaban_user = $pertanyaan->jawaban_user;
    
        //                         if($jawaban_user == "Sangat Yakin"){
        //                             $cf_user = 1;
        //                         }else if ($jawaban_user == "Yakin"){
        //                             $cf_user = 0.8;
        //                         }else if($jawaban_user == "Cukup Yakin"){
        //                             $cf_user = 0.6;
        //                         }else if($jawaban_user == "Sedikit Yakin"){
        //                             $cf_user = 0.4;
        //                         }elseif($jawaban_user == "Tidak Yakin"){
        //                             $cf_user = 0.2;
        //                         }elseif($jawaban_user == "Tidak Sama Sekali"){
        //                             $cf_user = 0;
        //                         }
        //                         $data_cfs = CertaintyFactor::where('id_penyakit',$id_penyakit)
        //                         ->where('id_gejala',$id_gejala)
        //                         ->get();
    
        //                         $hasil_cf_komb = 0;
        //                         $first_iteration = true;
        //                         if($data_cfs->isEmpty()){
        //                             return response()->json(['message' => 'Data CFS Tidak Ditemukan'], 422);
        //                         }else{
        //                             // return response()->json($data_cfs, 200);
        //                             foreach($data_cfs as $data_cf){
        //                                 $cf_pakar = $data_cf->certainty_factor;
        //                                 $hasil_cf = $cf_user * $cf_pakar;
        //                                 if ($first_iteration) {
        //                                     $hasil_cf_komb = $hasil_cf;
        //                                     $first_iteration = false;
        //                                 } else {
        //                                     $hasil_cf_komb = $hasil_cf_komb + $hasil_cf * (1 - $hasil_cf_komb);
        //                                 }
        //                             }
        //                         }
        //                     }
    
        //                 } 
        //                 // Simpan hasil kombinasi CF untuk penyakit ini
        //                 $results[] = [
        //                     'id_penyakit' => $id_penyakit,
        //                     'nama_penyakit' => $nama_penyakit,
        //                     'hasil_cf_komb' => $hasil_cf_komb
        //                 ];
        //                 // $results[] = [
        //                 //     'id_penyakit' => $id_penyakit,
        //                 //     'nama_penyakit' => $nama_penyakit,
        //                 //     'cf_pakar' => $cf_pakar,
        //                 // ];
        //             }
        //         }  
                
        //         return response()->json($results, 200);                        
        //     }else{
        //         return response()->json(['message' => 'No data found'], 422);
        //     }
        // }
    
}
