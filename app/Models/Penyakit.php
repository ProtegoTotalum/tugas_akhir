<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penyakit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penyakit',
        'deskripsi_penyakit',
        'gejala_penyakit',
        'penyebab_penyakit',
        'penyebaran_penyakit',
        'cara_pencegahan',
        'cara_penanganan',
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
}
