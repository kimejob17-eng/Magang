<?php

namespace App\Models\Pengaturan;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = 'platform';
    public $timestamps = false;

    protected $fillable = ['nama', 'slug', 'ikon', 'kode_warna', 'is_aktif'];
}