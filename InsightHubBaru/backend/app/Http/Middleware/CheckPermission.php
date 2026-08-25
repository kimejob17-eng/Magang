<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $menuDetailSlug
     * @param  string  $permissionSlug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $menuDetailSlug, $permissionSlug): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->role_id) {
            abort(403, 'Unauthorized');
        }

        // Cek permission dari database (Super Admin otomatis lolos jika di seeder sudah mendapat semua mapping)
        $hasPermission = $user->roleModel->permissions()
            ->whereHas('menuDetail', function ($query) use ($menuDetailSlug) {
                $query->where('slug', $menuDetailSlug);
            })
            ->whereHas('permission', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Forbidden: Access Denied by RBAC.');
        }

        return $next($request);
    }
}
