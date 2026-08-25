<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'urutan', 'is_aktif'];

    public function details(): HasMany
    {
        return $this->hasMany(MenuDetail::class, 'menu_id');
    }
}
