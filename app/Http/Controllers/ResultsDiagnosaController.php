<?php

namespace App\Http\Controllers;

use App\Http\Resources\TAResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsDiagnosaController extends Controller
{
    public function showResults($id_diagnosa){
        $results = DB::table('results_diagnosas')
        ->join('diagnosas', 'results_diagnosas.id_diagnosa', '=', 'diagnosas.id')
        ->join('penyakits', 'results_diagnosas.id_penyakit', '=', 'penyakits.id')
        ->select(
            'results_diagnosas.id as id_result_diagnosas',
            'diagnosas.id as id_diagnosa',
            'penyakits.nama_penyakit as nama_penyakit',
            'results_diagnosas.hasil_cf_komb as hasil_cf_komb',
            'results_diagnosas.hasil_cf_komb_persen as hasil_cf_komb_persen'
        )
        ->where('results_diagnosas.id_diagnosa',$id_diagnosa)
        ->orderBy('results_diagnosas.hasil_cf_komb_persen', 'desc')
        ->get();
        if(count($results) > 0){
            return new TAResource(true, 'List Data Result Diagnosa',
            $results); // return data semua diagnosa dalam bentuk json
        }

        return response([
            'message' => 'Data Result Diagnosa Tidak Ditemukan',
            'data' => null
        ], 400); // return message data result diagnosa kosong
    }
}
