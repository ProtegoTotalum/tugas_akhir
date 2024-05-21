<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CertaintyFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penyakit',
        'id_gejala',
        'certainty_factor',
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

    public function penyakit(){
        return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id');
    }

    public function gejala(){
        return $this->belongsTo(Gejala::class, 'id_gejala', 'id');
    }
}
