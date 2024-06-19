<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Diagnosa;
use App\Models\CertaintyFactor;
use App\Models\Penyakit;
use App\Models\Pertanyaan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use App\Models\AnalisaDokter;
use App\Models\ResultsDiagnosa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DiagnosaController extends Controller
{
    public function diagnosa($id,$id_user_dokter){
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
            $dokter_user = $id_user_dokter;
            $nomor_diagnosa = $diagnosa->nomor_diagnosa;
            $id_diagnosa = $diagnosa->id;
            $penyakits = Penyakit::latest()->get();
            $results = [];
            $resultsperpertanyaan = [];
            $cf_values = [];
            $cf_users = [];
            $track_hasil_komb = [];
        
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
                        $cf_values = [];
        
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
                            }elseif($jawaban_user == "Tidak Merasakan Sama Sekali"){
                                $cf_user = 0;
                            }

                            $data_cfs = CertaintyFactor::where('id_penyakit', $id_penyakit)
                                ->where('id_gejala', $id_gejala)
                                ->first();
    
                            $cf_pakar = $data_cfs->certainty_factor;
                            $hasil_cf = round($cf_user * $cf_pakar,2);
                            if($hasil_cf == 0){
                                continue;
                            } else {
                                $cf_values[] = $hasil_cf;
                            }
                        
                        }
        
                        // Calculate cf_kombinasi from cf_values
                        if (!empty($cf_values)) {
                            $hasil_cf_komb = $cf_values[0];
                            $first_cf = $hasil_cf_komb;
                            $second_cf = $cf_values[1];
                            foreach (array_slice($cf_values, 1) as $cf_value) {
                                $track_cf_value = $cf_value;
                                $temp = round($hasil_cf_komb + $cf_value,4);
                                $track_temp = $temp; 
                                $kurung = round(1 - $hasil_cf_komb,4);
                                $track_kurung = $kurung;
                                $hasil_cf_komb = round($temp * $kurung,4);
                                $track_komb = $hasil_cf_komb;
                                // $temp = $hasil_cf_komb + $cf_value;

                                // $kurung = 1 - $hasil_cf_komb;
                                
                                // $hasil_cf_komb = $temp * $kurung;
                                
                                $track_hasil_komb[] = [
                                    'track_cf_value' => $track_cf_value,
                                    'track_temp' => $track_temp,
                                    'track_kurung' => $track_kurung,
                                    'hasil_cf_komb' => $track_komb,
                                ];
                            }
                            $hasil_cf_komb_persen = round($hasil_cf_komb * 100,2);
                            $results[] = [
                                'id_penyakit' => $id_penyakit,
                                'nama_penyakit' => $nama_penyakit,
                                'hasil_cf_komb' => $hasil_cf_komb,
                                'hasil_cf_komb_persen' => $hasil_cf_komb_persen,
                                // 'cf_values' => $cf_values,
                                // 'first_cf' => $first_cf,
                                // 'second_cf' => $second_cf,
                                // 'track_hasil' => $track_hasil_komb
                            ];
                            $resultsDiagnosa = ResultsDiagnosa::create([
                                'id_diagnosa' => $id_diagnosa,
                                'id_penyakit' => $id_penyakit,
                                'hasil_cf_komb' => $hasil_cf_komb,
                                'hasil_cf_komb_persen' => $hasil_cf_komb_persen,
                            ]);
                            $cf_values = array_filter($cf_values, function($value) { return false; });
                            $track_hasil_komb = array_filter($track_hasil_komb, function($value) { return false; });
                        }
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
                $hasil_persentase_result = number_format($hasil_persentase, 2);
                $id_penyakit_result = $max_result['id_penyakit'];
                $diagnosa_update = Diagnosa::find($id_diagnosa);
                $diagnosa_update->persentase_hasil = $hasil_persentase_result;
                $diagnosa_update->id_penyakit = $id_penyakit_result;
                $diagnosa_update->id_dokter = $dokter_user;
                $diagnosa_update->konfirmasi_dokter = 0;
                $formattedTgl = Carbon::now()->format('Y-m-d');
                $formattedJam = Carbon::now()->format('H:i:s');
                $diagnosa_update->tanggal_diagnosa = $formattedTgl;
                $diagnosa_update->jam_diagnosa = $formattedJam;
                $diagnosa_update->save();
                $zero = 0;

                $analisa = AnalisaDokter::create([
                    'id_diagnosa' => $id_diagnosa,
                    'id_dokter' => $dokter_user,
                    'reminder_analisa' => $zero,
                    'status_analisa' => $zero
                ]);
            }
        
            return response()->json([
                'results' => $results,
                'max_result' => $max_result,
            ], 200);
        } else {
            return response()->json(['message' => 'No data found'], 422);
        }
    }

    public function showDiagnosaUser($id_user, $id_diagnosa)
    {
        $diagnosa = DB::table('diagnosas')
        ->join('users as users1', 'diagnosas.id_user', '=', 'users1.id')
        ->join('penyakits', 'diagnosas.id_penyakit', '=', 'penyakits.id')
        ->join('users as dokters', 'diagnosas.id_dokter', '=', 'dokters.id')
        ->select(
            'diagnosas.id as id_diagnosa',
            'diagnosas.id_user as id_user',
            'diagnosas.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'users1.nama_user as nama_user',
            'users1.tgl_lahir_user as tgl_lahir_user',
            'users1.umur_user as umur_user',
            'users1.bb_user as bb_user',
            'users1.tinggi_user as tinggi_user',
            'users1.gender_user as gender_user',
            'penyakits.nama_penyakit as nama_penyakit',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'dokters.nama_user as nama_dokter'
        )
        ->where('diagnosas.id_user',$id_user)
        ->where('diagnosas.id',$id_diagnosa)
        ->get();
        if(count($diagnosa) > 0){
            return new TAResource(true, 'List Data Diagnosa',
            $diagnosa); // return data semua diagnosa dalam bentuk json
        }

        return response([
            'message' => 'Data Diagnosa Tidak Ditemukan',
            'data' => null
        ], 400); // return message data diagnosa kosong
    }

    public function showDiagnosaUserAll($id)
    {
        $diagnosa = DB::table('diagnosas')
        ->join('users as users1', 'diagnosas.id_user', '=', 'users1.id')
        ->join('penyakits', 'diagnosas.id_penyakit', '=', 'penyakits.id')
        ->join('users as dokters', 'diagnosas.id_dokter', '=', 'dokters.id')
        ->select(
            'diagnosas.id as id_diagnosa',
            'diagnosas.id_user as id_user',
            'diagnosas.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'users1.nama_user as nama_user',
            'users1.tgl_lahir_user as tgl_lahir_user',
            'users1.umur_user as umur_user',
            'users1.bb_user as bb_user',
            'users1.tinggi_user as tinggi_user',
            'users1.gender_user as gender_user',
            'penyakits.nama_penyakit as nama_penyakit',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'dokters.nama_user as nama_dokter'
        )
        ->where('diagnosas.id_user',$id)
        ->orderBy('diagnosas.nomor_diagnosa_user', 'desc')
        ->get();
        if(count($diagnosa) > 0){
            return new TAResource(true, 'List Data Diagnosa',
            $diagnosa); // return data semua diagnosa dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data diagnosa kosong
    }

    public function lastDiagnosa($id){
        $diagnosa = DB::table('diagnosas')
        ->join('users as users1', 'diagnosas.id_user', '=', 'users1.id')
        ->join('penyakits', 'diagnosas.id_penyakit', '=', 'penyakits.id')
        ->join('users as dokters', 'diagnosas.id_dokter', '=', 'dokters.id')
        ->select(
            'diagnosas.id as id_diagnosa',
            'diagnosas.id_user as id_user',
            'diagnosas.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'users1.nama_user as nama_user',
            'users1.tgl_lahir_user as tgl_lahir_user',
            'users1.umur_user as umur_user',
            'users1.bb_user as bb_user',
            'users1.tinggi_user as tinggi_user',
            'users1.gender_user as gender_user',
            'penyakits.nama_penyakit as nama_penyakit',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'dokters.nama_user as nama_dokter'
        )
        ->where('diagnosas.id_user',$id)
        ->orderBy('diagnosas.created_at', 'desc')
        ->first();

        if(!is_null($diagnosa)){
            return response([
                'stt' => 'true',
                'message' => 'Data Diagnosa Ditemukan',
                'data' => [$diagnosa]
            ], 200);
        }

        return response([
            'message' => 'Data Diagnosa Tidak Ditemukan',
            'data' => null
        ], 404);  //return message data diagnosa kosong
    }


    // public function diagnosa($id){
    //     $diagnosa = DB::table('diagnosas')
    //     ->join('users', 'diagnosas.id_user', '=', 'users.id')
    //     ->select(
    //         'diagnosas.id as id',
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
    //         $id_diagnosa = $diagnosa->id;
    //         $penyakits = Penyakit::latest()->get();
    //         $results = [];
    //         $resultsperpertanyaan = [];
        
    //         if ($penyakits->isEmpty()) {
    //             return response()->json(['message' => 'Data Penyakit Tidak Ditemukan'], 422);
    //         } else {
    //             foreach ($penyakits as $penyakit) {
    //                 $id_penyakit = $penyakit->id;
    //                 $nama_penyakit = $penyakit->nama_penyakit;
        
    //                 $pertanyaans = Pertanyaan::where('nomor_diagnosa_pertanyaan', $nomor_diagnosa)->get();
    //                 if ($pertanyaans->isEmpty()) {
    //                     return response()->json(['message' => 'No Data Pertanyaan Found'], 422);
    //                 } else {
    //                     $cf_values = [];
    //                     $cf_users = [];
        
    //                     foreach ($pertanyaans as $pertanyaan) {
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
    //                         }elseif($jawaban_user == "Tidak Merasakan Sama Sekali"){
    //                             $cf_user = 0;
    //                         }
        
    //                         // if ($cf_user === 0) {
    //                         //     continue;
    //                         // } else {
    //                             $data_cfs = CertaintyFactor::where('id_penyakit', $id_penyakit)
    //                                 ->where('id_gejala', $id_gejala)
    //                                 ->first();
        
    //                             // if (is_null($data_cfs)) {
    //                             //     continue;
    //                             // }
        
    //                             $cf_pakar = $data_cfs->certainty_factor;
    //                             $hasil_cf = $cf_user * $cf_pakar;
    //                             if($hasil_cf == 0){
    //                                 continue;
    //                             }else{
    //                                 $cf_values[] = $hasil_cf;
    //                             }
    //                             $cf_users[] = $cf_user;
    //                             $resultsperpertanyaan[] = [
    //                                 'id_penyakit' => $id_penyakit,
    //                                 'id_gejala' => $id_gejala,
    //                                 'jawaban_user' => $jawaban_user,
    //                                 'cf_user' => $cf_user,
    //                                 'cf_pakar' => $cf_pakar,
    //                                 'hasil_cf' => $hasil_cf,
    //                             ];
                                
    //                         // }
    //                     }
    //                     // Calculate cf_kombinasi from cf_values
    //                     // $hasil_cf_komb = array_shift($cf_values);
    //                     $hasil_cf_komb = $cf_values[0];
    //                     foreach ($cf_values as $cf_value) {
    //                         // $hasil_cf_komb = ($hasil_cf_komb + $cf_value) * (1 - $hasil_cf_komb);
    //                         $temp = $hasil_cf_komb + $cf_value;
    //                         $kurung = 1 - $hasil_cf_komb;
    //                         $hasil_cf_komb = $temp * $kurung;
    //                     }
    
    //                     $hasil_cf_komb_persen = $hasil_cf_komb * 100;
    //                     $results[] = [
    //                         'id_penyakit' => $id_penyakit,
    //                         'nama_penyakit' => $nama_penyakit,
    //                         'hasil_cf_komb' => $hasil_cf_komb,
    //                         'hasil_cf_komb_persen' => $hasil_cf_komb_persen,
    //                         'cf_values' => $cf_values,
    //                     ];
                        
    //                 }
    //             }
    //         }
    //         // return response()->json([
    //         //     'results' => $results,
    //         //     'resultsperpertanyaan' => $resultsperpertanyaan,
    //         // ], 200);
    //         // return response()->json([
    //         //     'results' => $results,
    //         // ], 200);
    //         $max_result = null;
    //         foreach ($results as $result) {
    //             if (is_null($max_result) || $result['hasil_cf_komb'] > $max_result['hasil_cf_komb']) {
    //                 $max_result = $result;
    //             }
    //         }
        
    //         if (!is_null($max_result)) {
    //             $hasil_persentase = $max_result['hasil_cf_komb_persen'];
    //             $hasil_persentase_result = number_format($hasil_persentase, 2);
    //             $id_penyakit_result = $max_result['id_penyakit'];
    //             $diagnosa_update = Diagnosa::find($id_diagnosa);
    //             $diagnosa_update->persentase_hasil = $hasil_persentase_result;
    //             $diagnosa_update->id_penyakit = $id_penyakit_result;
    //             $diagnosa_update->konfirmasi_dokter = 0;
    //             $formattedTgl = Carbon::now()->format('Y-m-d');
    //             $formattedJam = Carbon::now()->format('H:i:s');
    //             $diagnosa_update->tanggal_diagnosa = $formattedTgl;
    //             $diagnosa_update->jam_diagnosa = $formattedJam;
    //             $diagnosa_update->save();
    //         }
        
    //         return response()->json([
    //             'results' => $results,
    //             'max_result' => $max_result
    //         ], 200);
    //     } else {
    //         return response()->json(['message' => 'No data found'], 422);
    //     }
    // }
    
}
