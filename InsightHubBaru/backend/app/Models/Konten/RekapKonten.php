<?php

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;

/**
 * Model READ-ONLY di atas VIEW v_rekap_konten (lihat schema v6, section 5).
 * JANGAN dipakai untuk create()/update()/delete() -> insert ke tabel per
 * platform lewat KontenFacebook/KontenInstagram/dst, bukan lewat model ini.
 *
 * Catatan: id_konten TIDAK unik lintas platform (tiap tabel sumber punya
 * id sendiri), jadi jangan pakai find($id) di sini -> selalu filter pakai
 * kombinasi sumber_tabel + id_konten kalau perlu row spesifik.
 */
class RekapKonten extends Model
{
    protected $table = 'v_rekap_konten';
    protected $primaryKey = 'id_konten';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = ['*']; // cegah mass-assignment / create() tak sengaja
}