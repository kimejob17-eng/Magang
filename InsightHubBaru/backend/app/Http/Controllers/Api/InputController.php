<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenInstagram;
use App\Models\Konten\KontenFacebook;
use App\Models\Konten\KontenTiktok;
use App\Models\Konten\KontenYoutubeVideo;
use App\Models\Konten\KontenYoutubeShorts;
use App\Models\Konten\KontenYoutubeLive;
use App\Models\Konten\KategoriKonten;
use App\Models\Pengaturan\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InputController extends Controller
{
    /**
     * Rule validasi yang identik untuk ketiga platform (Instagram/Facebook/TikTok).
     * Direplikasi dari DashboardController@storeMetric, tanpa rule 'platform'
     * karena masing-masing endpoint sudah spesifik per platform.
     */
    private function commonValidationRules(): array
    {
        return [
            'title'         => ['nullable', 'string'],
            'category'      => ['nullable', 'string'],
            'publish_date'  => ['nullable', 'date'],
            'content_type'  => ['nullable', 'string'],
            'url'           => ['nullable', 'url'],
            'views'         => ['nullable', 'numeric'],
            'interactions'  => ['nullable', 'numeric'],
            'like'          => ['nullable', 'numeric'],
            'comment'       => ['nullable', 'numeric'],
            'share'         => ['nullable', 'numeric'],
        ];
    }

    /**
     * Resolusi/pembuatan kategori berdasarkan slug platform tertentu.
     * Mengembalikan ID kategori, atau null jika field 'category' kosong
     * atau platform dengan slug tersebut tidak ditemukan.
     */
    private function resolveKategoriId(?string $categoryName, string $platformSlug): ?int
    {
        if (empty($categoryName)) {
            return null;
        }

        $platformRow = Platform::where('slug', $platformSlug)->first();

        if (!$platformRow) {
            return null;
        }

        $kategori = KategoriKonten::firstOrCreate([
            'nama'        => $categoryName,
            'platform_id' => $platformRow->id,
        ]);

        return $kategori->id;
    }

    /**
     * POST /api/dashboard/input/instagram
     * Catatan pemetaan unik: 'views' -> kolom 'jangkauan' (bukan 'tayangan').
     * Default content_type: "Feed Post".
     */
    public function storeInstagram(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->commonValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId($validated['category'] ?? null, 'instagram');

        $instagramData = KontenInstagram::create([
            'kategori_id'     => $kategoriId,
            'judul'           => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'          => $validated['url'] ?? null,
            'tanggal_tayang'  => $validated['publish_date'] ?? null,
            'suka'            => $validated['like'] ?? 0,
            'komentar'        => $validated['comment'] ?? 0,
            'dibagikan'       => $validated['share'] ?? 0,
            'jenis_konten'    => $validated['content_type'] ?? 'Feed Post',
            'jangkauan'       => $validated['views'] ?? 0,
            'total_interaksi' => $validated['interactions'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa Instagram berhasil ditambahkan.',
            'data'    => $instagramData,
        ], 201);
    }

    /**
     * POST /api/dashboard/input/facebook
     * Pemetaan: 'views' -> kolom 'tayangan'.
     * Default content_type: "Image Post".
     */
    public function storeFacebook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->commonValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId($validated['category'] ?? null, 'facebook');

        $facebookData = KontenFacebook::create([
            'kategori_id'     => $kategoriId,
            'judul'           => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'          => $validated['url'] ?? null,
            'tanggal_tayang'  => $validated['publish_date'] ?? null,
            'suka'            => $validated['like'] ?? 0,
            'komentar'        => $validated['comment'] ?? 0,
            'dibagikan'       => $validated['share'] ?? 0,
            'jenis_konten'    => $validated['content_type'] ?? 'Image Post',
            'tayangan'        => $validated['views'] ?? 0,
            'total_interaksi' => $validated['interactions'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa Facebook berhasil ditambahkan.',
            'data'    => $facebookData,
        ], 201);
    }

    /**
     * POST /api/dashboard/input/tiktok
     * Pemetaan: 'views' -> kolom 'tayangan' (sama seperti Facebook).
     * Default content_type: "Short Video".
     */
    public function storeTiktok(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->commonValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId($validated['category'] ?? null, 'tiktok');

        $tiktokData = KontenTiktok::create([
            'kategori_id'     => $kategoriId,
            'judul'           => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'          => $validated['url'] ?? null,
            'tanggal_tayang'  => $validated['publish_date'] ?? null,
            'suka'            => $validated['like'] ?? 0,
            'komentar'        => $validated['comment'] ?? 0,
            'dibagikan'       => $validated['share'] ?? 0,
            'jenis_konten'    => $validated['content_type'] ?? 'Short Video',
            'tayangan'        => $validated['views'] ?? 0,
            'total_interaksi' => $validated['interactions'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa TikTok berhasil ditambahkan.',
            'data'    => $tiktokData,
        ], 201);
    }

    /**
     * Resolusi/pembuatan kategori khusus YouTube.
     * Berbeda dari resolveKategoriId() biasa: ketiga varian YouTube
     * (Video/Shorts/Live) selalu meresolve ke SATU platform induk
     * berslug 'youtube', bukan ke slug varian itu sendiri.
     */
    private function getYoutubeKategoriId(?string $categoryName): ?int
    {
        if (empty($categoryName)) {
            return null;
        }

        $platformRow = Platform::where('slug', 'youtube')->first();

        if (!$platformRow) {
            return null;
        }

        return KategoriKonten::firstOrCreate([
            'nama'        => $categoryName,
            'platform_id' => $platformRow->id,
        ])->id;
    }

    /**
     * Rule validasi umum untuk YouTube Video & Shorts (payload identik).
     * Tidak ada rule 'content_type' atau 'interactions' — kedua field ini
     * tidak tersedia di skema database YouTube.
     */
    private function youtubeValidationRules(): array
    {
        return [
            'title'        => ['nullable', 'string'],
            'category'     => ['nullable', 'string'],
            'publish_date' => ['nullable', 'date'],
            'url'          => ['nullable', 'url'],
            'views'        => ['nullable', 'numeric'],
            'like'         => ['nullable', 'numeric'],
            'comment'      => ['nullable', 'numeric'],
            'share'        => ['nullable', 'numeric'],
            'subscribers'  => ['nullable', 'numeric'],
        ];
    }

    /**
     * POST /api/dashboard/input/youtube/video
     */
    public function storeYoutubeVideo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->youtubeValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId($validated['category'] ?? null);

        $videoData = KontenYoutubeVideo::create([
            'kategori_id'           => $kategoriId,
            'judul'                 => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'                => $validated['url'] ?? null,
            'tanggal_tayang'        => $validated['publish_date'] ?? null,
            'suka'                  => $validated['like'] ?? 0,
            'komentar'              => $validated['comment'] ?? 0,
            'dibagikan'             => $validated['share'] ?? 0,
            'jumlah_penayangan'     => $validated['views'] ?? 0,
            'penambahan_subscriber' => $validated['subscribers'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa YouTube Video berhasil ditambahkan.',
            'data'    => $videoData,
        ], 201);
    }

    /**
     * POST /api/dashboard/input/youtube/shorts
     */
    public function storeYoutubeShorts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->youtubeValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId($validated['category'] ?? null);

        $shortsData = KontenYoutubeShorts::create([
            'kategori_id'           => $kategoriId,
            'judul'                 => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'                => $validated['url'] ?? null,
            'tanggal_tayang'        => $validated['publish_date'] ?? null,
            'suka'                  => $validated['like'] ?? 0,
            'komentar'              => $validated['comment'] ?? 0,
            'dibagikan'             => $validated['share'] ?? 0,
            'jumlah_penayangan'     => $validated['views'] ?? 0,
            'penambahan_subscriber' => $validated['subscribers'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa YouTube Shorts berhasil ditambahkan.',
            'data'    => $shortsData,
        ], 201);
    }

    /**
     * POST /api/dashboard/input/youtube/live
     * Satu-satunya varian YouTube dengan field tambahan 'peak_viewers'
     * yang dipetakan ke kolom 'penonton_puncak'.
     */
    public function storeYoutubeLive(Request $request): JsonResponse
    {
        $rules = $this->youtubeValidationRules();
        $rules['peak_viewers'] = ['nullable', 'numeric'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId($validated['category'] ?? null);

        $liveData = KontenYoutubeLive::create([
            'kategori_id'           => $kategoriId,
            'judul'                 => $validated['title'] ?? '(Tanpa Judul)',
            'tautan'                => $validated['url'] ?? null,
            'tanggal_tayang'        => $validated['publish_date'] ?? null,
            'suka'                  => $validated['like'] ?? 0,
            'komentar'              => $validated['comment'] ?? 0,
            'dibagikan'             => $validated['share'] ?? 0,
            'jumlah_penayangan'     => $validated['views'] ?? 0,
            'penambahan_subscriber' => $validated['subscribers'] ?? 0,
            'penonton_puncak'       => $validated['peak_viewers'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data performa YouTube Live berhasil ditambahkan.',
            'data'    => $liveData,
        ], 201);
    }
}