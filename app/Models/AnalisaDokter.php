<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisaDokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_diagnosa',
        'id_dokter',
        'analisa_dokter',
        'tanggal_analisa',
        'jam_analisa',
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

    public function dokter(){
        return $this->belongsTo(User::class, 'id_dokter', 'id');
    }

}
