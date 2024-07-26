<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BahanMakanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bahan_makanan',
        'golongan_bahan_makanan',
        'takaran(g)',
        'kalori',
        'karbohidrat',
        'protein_nabati',
        'protein_hewani',
        'lemak',
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
