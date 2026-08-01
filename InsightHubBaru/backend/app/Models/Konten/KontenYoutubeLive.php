<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;

class KontenYoutubeLive extends Model
{
    protected $table = 'konten_youtube_live';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'kategori_id', 'judul', 'tautan', 'tanggal_tayang',
        'jumlah_penayangan', 'penambahan_subscriber', 'penonton_puncak',
        'suka', 'komentar', 'dibagikan', 'diinput_oleh',
    ];

    protected $casts = ['tanggal_tayang' => 'date'];

    public function kategori()
    {
        return $this->belongsTo(KategoriKonten::class, 'kategori_id');
    }
}