<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'must_change_password', 'role', 'role_id',
        'phone', 'location', 'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission(string $menuDetailSlug, string $permissionSlug = 'view'): bool
    {
        if (!$this->role_id) {
            return false;
        }

        // Jika user adalah Super Admin, pastikan dia tetap mengikuti seeder tetapi
        // kita izinkan memiliki semua akses jika data mapping diset
        return $this->roleModel->permissions()
            ->whereHas('menuDetail', function ($query) use ($menuDetailSlug) {
                $query->where('slug', $menuDetailSlug);
            })
            ->whereHas('permission', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();
    }
}