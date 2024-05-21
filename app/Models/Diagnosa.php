<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Diagnosa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_penyakit',
        'nomor_diagnosa_user',
        'nomor_diagnosa',
        'persentase_hasil',
        'tanggal_diagnosa',
        'jam_diagnosa',
        'konfirmasi_dokter',
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function penyakit(){
        return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id');
    }
}
