<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Diagnosa;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TAResource;
use App\Models\AnalisaDokter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalisaDokterController extends Controller
{
    public function analisaDokter($id_diagnosa){
        $analisa = DB::table('analisa_dokters')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->select(
            'analisa_dokters.id as id_analisa',
            'analisa_dokters.id_diagnosa as id_diagnosa',
            'diagnosas.id_user as id_user',
            'diagnosas.id_penyakit as id_penyakit',
            'diagnosas.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'diagnosas.konfirmasi_dokter as konfirmasi_dokter'
        )
        ->where('analisa_dokters.id_diagnosa',$id_diagnosa)
        ->get();

        
    }
}
