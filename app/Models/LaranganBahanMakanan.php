<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LaranganBahanMakanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_bahan_makanan',
        'id_penyakit',
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

    public function bahan_makanan(){
        return $this->belongsTo(BahanMakanan::class, 'id_bahan_makanan', 'id');
    }

    public function penyakit(){
        return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id');
    }
}
