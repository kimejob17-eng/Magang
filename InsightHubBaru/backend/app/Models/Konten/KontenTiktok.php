<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;

class KontenTiktok extends Model
{
    protected $table = 'konten_tiktok';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'kategori_id', 'judul', 'jenis_konten', 'tautan', 'tanggal_tayang',
        'tayangan', 'total_interaksi', 'saves', 'likes', 'comments', 'shares',
        'diinput_oleh',
    ];

    protected $casts = ['tanggal_tayang' => 'date'];

    public function kategori()
    {
        return $this->belongsTo(KategoriKonten::class, 'kategori_id');
    }
}