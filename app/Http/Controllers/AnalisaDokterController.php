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
    public function getAnalisaByDokter($id_dokter){
        $analisa = DB::table('analisa_dokters')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->join('users as dokter', 'analisa_dokters.id_dokter', '=', 'dokter.id')
        ->join('users as pasien', 'diagnosas.id_user', '=', 'pasien.id')
        ->join('penyakits', 'diagnosas.id_penyakit', '=', 'penyakits.id')
        ->select(
            'analisa_dokters.id as id_analisa',
            'analisa_dokters.id_diagnosa as id_diagnosa',
            'diagnosas.id_user as id_pasien',
            'diagnosas.id_penyakit as id_penyakit',
            'analisa_dokters.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'pasien.nama_user as nama_pasien',
            'pasien.tgl_lahir_user as tgl_lahir_pasien',
            'pasien.umur_user as umur_pasien',
            'pasien.bb_user as bb_pasien',
            'pasien.tinggi_user as tinggi_pasien',
            'pasien.gender_user as gender_pasien',
            'dokter.nama_user as nama_dokter',
            'penyakits.nama_penyakit as nama_penyakit',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'analisa_dokters.catatan_dokter as catatan_dokter',
            'analisa_dokters.tanggal_analisa as tanggal_analisa',
            'analisa_dokters.jam_analisa as jam_analisa',
            'analisa_dokters.reminder_analisa as reminder_analisa',
            'analisa_dokters.status_analisa as status_analisa',
            'diagnosas.konfirmasi_dokter as konfirmasi_dokter'
        )
        ->where('analisa_dokters.id_dokter',$id_dokter)
        ->where('analisa_dokters.status_analisa', 0)
        ->orderBy('analisa_dokters.id', 'desc')
        ->get();

        if(count($analisa) > 0){
            return new TAResource(true, 'List Data Analisa Dokter',
            $analisa); // return data semua analisa dokter dalam bentuk json
        }

        return response([
            'message' => 'Data Analisa Dokter Tidak Ditemukan',
            'data' => null
        ], 400); // return message data analisa dokter kosong
        
    }

    public function updateReminderAnalisa($id_analisa){
        $analisa = AnalisaDokter::find($id_analisa);
        $one = 1;

        if(!is_null($analisa)){
            $analisa->update([
                'reminder_analisa' => $one,
            ]);
            return response([
                'success' => 'true', 
                'message' => 'Reminder Sent',
            ], 200);
        }

        return response([
            'message' => 'Data Analisa Dokter Tidak Ditemukan',
        ], 404); // return message saat data analisa dokter tidak ditemukan
    }

    public function getAnalisaDetail($id_analisa){
        $analisa = DB::table('analisa_dokters')
        ->join('diagnosas', 'analisa_dokters.id_diagnosa', '=', 'diagnosas.id')
        ->join('users as dokter', 'analisa_dokters.id_dokter', '=', 'dokter.id')
        ->join('users as pasien', 'diagnosas.id_user', '=', 'pasien.id')
        ->join('penyakits', 'diagnosas.id_penyakit', '=', 'penyakits.id')
        ->select(
            'analisa_dokters.id as id_analisa',
            'analisa_dokters.id_diagnosa as id_diagnosa',
            'diagnosas.id_user as id_pasien',
            'diagnosas.id_penyakit as id_penyakit',
            'analisa_dokters.id_dokter as id_dokter',
            'diagnosas.nomor_diagnosa as nomor_diagnosa',
            'pasien.nama_user as nama_pasien',
            'pasien.tgl_lahir_user as tgl_lahir_pasien',
            'pasien.umur_user as umur_pasien',
            'pasien.bb_user as bb_pasien',
            'pasien.tinggi_user as tinggi_pasien',
            'pasien.gender_user as gender_pasien',
            'dokter.nama_user as nama_dokter',
            'penyakits.nama_penyakit as nama_penyakit',
            'diagnosas.persentase_hasil as persentase_hasil',
            'diagnosas.tanggal_diagnosa as tanggal_diagnosa',
            'diagnosas.jam_diagnosa as jam_diagnosa',
            'analisa_dokters.catatan_dokter as catatan_dokter',
            'analisa_dokters.tanggal_analisa as tanggal_analisa',
            'analisa_dokters.jam_analisa as jam_analisa',
            'analisa_dokters.reminder_analisa as reminder_analisa',
            'analisa_dokters.status_analisa as status_analisa',
            'diagnosas.konfirmasi_dokter as konfirmasi_dokter'
        )
        ->where('analisa_dokters.id',$id_analisa)
        ->first();

        if(!is_null($analisa)){
            return response([
                'message' => 'Data Detail Analisa Ditemukan',
                'data' => [$analisa]
            ], 200);
        }

        return response([
            'message' => 'Data Detail Analisa Tidak Ditemukan',
            'data' => null
        ], 404); // return message saat data detail analisa tidak ditemukan
        
    }

    public function verifikasi(Request $request, $id_analisa){
        $analisa = AnalisaDokter::find($id_analisa);

        $analisa->status_analisa = 1;
        $analisa->catatan_dokter = $request->catatan_dokter;
        $formattedTgl = Carbon::now()->format('Y-m-d');
        $formattedJam = Carbon::now()->format('H:i:s');
        $analisa->tanggal_analisa = $formattedTgl;
        $analisa->jam_analisa = $formattedJam;
        $analisa->save();

        $id_diagnosa = $analisa->id_diagnosa;
        $diagnosa = Diagnosa::find($id_diagnosa);
        $diagnosa->konfirmasi_dokter = 1;
        $diagnosa->save();

        return new TAResource(true, 'Analisa Berhasil Diverifikasi!', [$analisa]);
    }
}
