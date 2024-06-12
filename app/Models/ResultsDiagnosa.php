<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ResultsDiagnosa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_diagnosa',
        'id_penyakit',
        'hasil_cf_komb',
        'hasil_cf_komb_persen'
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

    public function diagnosa(){
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa', 'id');
    }

    public function penyakit(){
        return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id');
    }
}
