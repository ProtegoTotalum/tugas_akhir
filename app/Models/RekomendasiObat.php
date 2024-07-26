<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RekomendasiObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_obat',
        'id_analisa',
        'aturan_minum_obat',
        'catatan_minum_obat',
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

    public function obat(){
        return $this->belongsTo(Obat::class, 'id_obat', 'id');
    }

    public function analisa(){
        return $this->belongsTo(AnalisaDokter::class, 'id_analisa', 'id');
    }
}
