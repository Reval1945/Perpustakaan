<?php

namespace App\Providers;

use App\Interfaces\BookInterface;
use App\Interfaces\UserInterface;
use App\Repositories\BookRepository;
use App\Repositories\UserRepository;
use App\Interfaces\CategoryInterface;
use App\Repositories\DendaRepository;
use App\Interfaces\PengunjungInterface;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\TransactionInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\PengunjungRepository;
use App\Repositories\TransactionRepository;
use App\Interfaces\AturanPeminjamanInterface;
use App\Interfaces\DendaInterface;
use App\Repositories\AturanPeminjamanRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            CategoryInterface::class,
            CategoryRepository::class
        );
        $this->app->bind(
            BookInterface::class,
            BookRepository::class
        );
        $this->app->bind(
            AturanPeminjamanInterface::class,
            AturanPeminjamanRepository::class
        );
        $this->app->bind(
            PengunjungInterface::class,
            PengunjungRepository::class
        );
        $this->app->bind(
            TransactionInterface::class,
            TransactionRepository::class
        );
        $this->app->bind(
            DendaInterface::class,
            DendaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
