<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;

class KontenYoutubeShorts extends Model
{
    protected $table = 'konten_youtube_shorts';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'kategori_id', 'judul', 'tautan', 'tanggal_tayang',
        'jumlah_penayangan', 'penambahan_subscriber',
        'suka', 'komentar', 'dibagikan', 'diinput_oleh',
    ];

    protected $casts = ['tanggal_tayang' => 'date'];

    public function kategori()
    {
        return $this->belongsTo(KategoriKonten::class, 'kategori_id');
    }
}