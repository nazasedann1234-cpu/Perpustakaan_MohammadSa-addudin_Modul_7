<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\User;
use App\Policies\AnggotaPolicy;
use App\Policies\PeminjamanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void {}


    public function boot(): void
    {
        // Di Vercel, HTTPS diterima di lapisan depan; paksa semua URL memakai https.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Pemeriksaan akses terhadap data anggota dan peminjaman.
        Gate::policy(Anggota::class, AnggotaPolicy::class);
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);

        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }
}