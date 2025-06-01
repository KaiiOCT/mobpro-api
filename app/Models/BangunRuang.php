<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BangunRuang extends Model
{
    protected $table = 'bangun-ruang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'gambar',
    ];

    public $timestamps = false;

    public function getGambarUrlAttribute()
    {
        return asset('storage/' . $this->gambar);
    }
}
