<?php

namespace App\Models\Konten;

use App\Models\Pengaturan\Platform;
use Illuminate\Database\Eloquent\Model;

class KategoriKonten extends Model
{
    protected $table = 'kategori_konten';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = ['nama', 'platform_id'];

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    /**
     * Pengganti $kategori->metrics()->exists() dari versi single-table.
     * Sebuah kategori cuma relevan ke SATU tabel konten (sesuai platform_id-nya),
     * kecuali YouTube yang punya 3 tabel (live/video/shorts).
     */
    public function hasRelatedMetrics(): bool
    {
        $slug = $this->platform->slug ?? null;

        return match ($slug) {
            'facebook'  => KontenFacebook::where('kategori_id', $this->id)->exists(),
            'instagram' => KontenInstagram::where('kategori_id', $this->id)->exists(),
            'tiktok'    => KontenTiktok::where('kategori_id', $this->id)->exists(),
            'youtube'   => KontenYoutubeLive::where('kategori_id', $this->id)->exists()
                         || KontenYoutubeVideo::where('kategori_id', $this->id)->exists()
                         || KontenYoutubeShorts::where('kategori_id', $this->id)->exists(),
            default     => false,
        };
    }
}