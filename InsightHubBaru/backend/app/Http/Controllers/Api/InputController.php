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
     * ============================================================
     * VALIDATION RULES
     * ============================================================
     */

    /**
     * Validasi Instagram.
     *
     * Field database:
     * - reach
     * - views
     * - likes
     * - comments
     * - shares
     */
    private function instagramValidationRules(): array
    {
        return [
            'title'        => ['nullable', 'string'],
            'category'     => ['nullable', 'string'],
            'publish_date' => ['required', 'date'],
            'content_type' => ['nullable', 'string'],
            'url'          => ['nullable', 'string'],

            'reach'        => ['nullable', 'string'],
            'views'        => ['nullable', 'string'],

            'like'         => ['nullable', 'string'],
            'comment'      => ['nullable', 'string'],
            'share'        => ['nullable', 'string'],
        ];
    }

    /**
     * Validasi Facebook.
     *
     * Field database:
     * - views
     * - saves
     * - likes
     * - comments
     * - shares
     */
    private function facebookValidationRules(): array
    {
        return [
            'title'        => ['nullable', 'string'],
            'category'     => ['nullable', 'string'],
            'publish_date' => ['required', 'date'],
            'content_type' => ['nullable', 'string'],
            'url'          => ['nullable', 'string'],

            'views'        => ['nullable', 'string'],
            'saves'        => ['nullable', 'string'],

            'like'         => ['nullable', 'string'],
            'comment'      => ['nullable', 'string'],
            'share'        => ['nullable', 'string'],
        ];
    }

    /**
     * Validasi TikTok.
     *
     * Field database:
     * - tayangan
     * - total_interaksi
     * - saves
     * - likes
     * - comments
     * - shares
     */
    private function tiktokValidationRules(): array
    {
        return [
            'title'        => ['nullable', 'string'],
            'category'     => ['nullable', 'string'],
            'publish_date' => ['required', 'date'],
            'content_type' => ['nullable', 'string'],
            'url'          => ['nullable', 'string'],

            'views'        => ['nullable', 'string'],
            'interactions' => ['nullable', 'string'],
            'saves'        => ['nullable', 'string'],

            'like'         => ['nullable', 'string'],
            'comment'      => ['nullable', 'string'],
            'share'        => ['nullable', 'string'],
        ];
    }

    /**
     * Validasi umum YouTube.
     *
     * Digunakan oleh:
     * - YouTube Video
     * - YouTube Shorts
     * - YouTube Live
     */
    private function youtubeValidationRules(): array
    {
        return [
            'title'        => ['nullable', 'string'],
            'category'     => ['nullable', 'string'],
            'publish_date' => ['required', 'date'],
            'url'          => ['nullable', 'url'],

            'views'        => ['nullable', 'string'],
            'like'         => ['nullable', 'string'],
            'comment'      => ['nullable', 'string'],
            'share'        => ['nullable', 'string'],
            'subscribers'  => ['nullable', 'string'],
        ];
    }

    /**
     * ============================================================
     * KATEGORI
     * ============================================================
     */

    /**
     * Mencari / membuat kategori berdasarkan platform.
     */
    private function resolveKategoriId(
        ?string $categoryName,
        string $platformSlug
    ): ?int {
        if (empty($categoryName)) {
            return null;
        }

        $platformRow = Platform::where(
            'slug',
            $platformSlug
        )->first();

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
     * Semua jenis YouTube menggunakan platform:
     *
     * slug = youtube
     */
    private function getYoutubeKategoriId(
        ?string $categoryName
    ): ?int {
        if (empty($categoryName)) {
            return null;
        }

        $platformRow = Platform::where(
            'slug',
            'youtube'
        )->first();

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
     * ============================================================
     * INSTAGRAM
     * ============================================================
     *
     * POST /api/dashboard/input/instagram
     *
     * Database:
     * - reach
     * - views
     * - likes
     * - comments
     * - shares
     */
    public function storeInstagram(
        Request $request
    ): JsonResponse {
        $validator = Validator::make(
            $request->all(),
            $this->instagramValidationRules()
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId(
            $validated['category'] ?? null,
            'instagram'
        );

        $instagramData = KontenInstagram::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'jenis_konten' => $validated['content_type']
                ?? 'Feed Post',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'reach' => $this->parseIndonesianMetric(
                $validated['reach'] ?? 0
            ),

            'views' => $this->parseIndonesianMetric(
                $validated['views'] ?? 0
            ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa Instagram berhasil ditambahkan.',
            'data' => $instagramData,
        ], 201);
    }

    /**
     * ============================================================
     * FACEBOOK
     * ============================================================
     *
     * POST /api/dashboard/input/facebook
     *
     * Database:
     * - views
     * - saves
     * - likes
     * - comments
     * - shares
     *
     * Facebook tidak menggunakan reach.
     */
    public function storeFacebook(
        Request $request
    ): JsonResponse {
        $validator = Validator::make(
            $request->all(),
            $this->facebookValidationRules()
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId(
            $validated['category'] ?? null,
            'facebook'
        );

        $facebookData = KontenFacebook::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'jenis_konten' => $validated['content_type']
                ?? 'Image Post',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'views' => $this->parseIndonesianMetric(
                $validated['views'] ?? 0
            ),

            'saves' => $this->parseIndonesianMetric(
                $validated['saves'] ?? 0
            ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa Facebook berhasil ditambahkan.',
            'data' => $facebookData,
        ], 201);
    }

    /**
     * ============================================================
     * TIKTOK
     * ============================================================
     *
     * POST /api/dashboard/input/tiktok
     *
     * Database:
     * - tayangan
     * - total_interaksi
     * - saves
     * - likes
     * - comments
     * - shares
     */
    public function storeTiktok(
        Request $request
    ): JsonResponse {
        $validator = Validator::make(
            $request->all(),
            $this->tiktokValidationRules()
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->resolveKategoriId(
            $validated['category'] ?? null,
            'tiktok'
        );

        $tiktokData = KontenTiktok::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'jenis_konten' => $validated['content_type']
                ?? 'Short Video',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'tayangan' => $this->parseIndonesianMetric(
                $validated['views'] ?? 0
            ),

            'total_interaksi' => $this->parseIndonesianMetric(
                $validated['interactions'] ?? 0
            ),

            'saves' => $this->parseIndonesianMetric(
                $validated['saves'] ?? 0
            ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa TikTok berhasil ditambahkan.',
            'data' => $tiktokData,
        ], 201);
    }

    /**
     * ============================================================
     * YOUTUBE VIDEO
     * ============================================================
     *
     * POST /api/dashboard/input/youtube/video
     *
     * Database:
     * - jumlah_penayangan
     * - penambahan_subscriber
     * - likes
     * - comments
     * - shares
     */
    public function storeYoutubeVideo(
        Request $request
    ): JsonResponse {
        $validator = Validator::make(
            $request->all(),
            $this->youtubeValidationRules()
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId(
            $validated['category'] ?? null
        );

        $videoData = KontenYoutubeVideo::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'jumlah_penayangan' =>
                $this->parseIndonesianMetric(
                    $validated['views'] ?? 0
                ),

            'penambahan_subscriber' =>
                $this->parseIndonesianMetric(
                    $validated['subscribers'] ?? 0
                ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa YouTube Video berhasil ditambahkan.',
            'data' => $videoData,
        ], 201);
    }

    /**
     * ============================================================
     * YOUTUBE SHORTS
     * ============================================================
     *
     * POST /api/dashboard/input/youtube/shorts
     *
     * Database:
     * - jumlah_penayangan
     * - penambahan_subscriber
     * - likes
     * - comments
     * - repost
     * - shares
     */
    public function storeYoutubeShorts(
        Request $request
    ): JsonResponse {
        $rules = $this->youtubeValidationRules();

        /*
         * Shorts memiliki field tambahan:
         * repost
         */
        $rules['repost'] = [
            'nullable',
            'string'
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId(
            $validated['category'] ?? null
        );

        $shortsData = KontenYoutubeShorts::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'jumlah_penayangan' =>
                $this->parseIndonesianMetric(
                    $validated['views'] ?? 0
                ),

            'penambahan_subscriber' =>
                $this->parseIndonesianMetric(
                    $validated['subscribers'] ?? 0
                ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            /*
             * YouTube Shorts memiliki REPOST.
             */
            'repost' => $this->parseIndonesianMetric(
                $validated['repost'] ?? 0
            ),

            /*
             * YouTube Shorts juga memiliki SHARES.
             */
            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa YouTube Shorts berhasil ditambahkan.',
            'data' => $shortsData,
        ], 201);
    }

    /**
     * ============================================================
     * YOUTUBE LIVE
     * ============================================================
     *
     * POST /api/dashboard/input/youtube/live
     *
     * Database:
     * - jumlah_penayangan
     * - penambahan_subscriber
     * - penonton_puncak
     * - likes
     * - comments
     * - shares
     */
    public function storeYoutubeLive(
        Request $request
    ): JsonResponse {
        $rules = $this->youtubeValidationRules();

        /*
         * Field khusus YouTube Live.
         */
        $rules['peak_viewers'] = [
            'nullable',
            'string'
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $kategoriId = $this->getYoutubeKategoriId(
            $validated['category'] ?? null
        );

        $liveData = KontenYoutubeLive::create([
            'kategori_id' => $kategoriId,

            'judul' => $validated['title']
                ?? '(Tanpa Judul)',

            'tautan' => $validated['url']
                ?? null,

            'tanggal_tayang' => $validated['publish_date'],

            'jumlah_penayangan' =>
                $this->parseIndonesianMetric(
                    $validated['views'] ?? 0
                ),

            'penambahan_subscriber' =>
                $this->parseIndonesianMetric(
                    $validated['subscribers'] ?? 0
                ),

            'penonton_puncak' =>
                $this->parseIndonesianMetric(
                    $validated['peak_viewers'] ?? 0
                ),

            'likes' => $this->parseIndonesianMetric(
                $validated['like'] ?? 0
            ),

            'comments' => $this->parseIndonesianMetric(
                $validated['comment'] ?? 0
            ),

            'shares' => $this->parseIndonesianMetric(
                $validated['share'] ?? 0
            ),
        ]);

        return response()->json([
            'success' => true,
            'message' =>
                'Data performa YouTube Live berhasil ditambahkan.',
            'data' => $liveData,
        ], 201);
    }

    /**
     * ============================================================
     * PARSE METRIC
     * ============================================================
     *
     * Mengubah format angka Indonesia menjadi integer.
     *
     * Contoh:
     *
     * 1000
     * "1000"
     * "1.000"
     * "1,5rb"
     * "2jt"
     * "1,5jt"
     */
    private function parseIndonesianMetric(
        $value
    ): int {
        if (is_null($value) || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        $value = strtolower(
            trim((string) $value)
        );

        $multiplier = 1;

        /**
         * Ribu
         */
        if (str_ends_with($value, 'rb')) {
            $multiplier = 1000;

            $value = str_replace(
                'rb',
                '',
                $value
            );
        }

        /**
         * Juta
         */
        elseif (str_ends_with($value, 'jt')) {
            $multiplier = 1000000;

            $value = str_replace(
                'jt',
                '',
                $value
            );
        }

        /**
         * Hapus titik pemisah ribuan.
         *
         * 1.000
         * 1.500.000
         */
        $value = str_replace(
            '.',
            '',
            $value
        );

        /**
         * Ubah koma desimal Indonesia
         * menjadi titik.
         *
         * 1,5 -> 1.5
         */
        $value = str_replace(
            ',',
            '.',
            $value
        );

        return (int) round(
            (float) $value * $multiplier
        );
    }
}