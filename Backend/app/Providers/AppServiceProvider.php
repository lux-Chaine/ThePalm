<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Database\UnitOfWork;
use App\Core\Database\UnitOfWorkInterface;
use App\Core\Bus\CommandBus;
use App\Core\Bus\QueryBus;
use App\Modules\Identity\Domain\UserRepositoryInterface;
use App\Modules\Identity\Infrastructure\EloquentUserRepository;
use App\Modules\Sales\Domain\ProductRepositoryInterface;
use App\Modules\Sales\Infrastructure\EloquentProductRepository;
use App\Modules\Sales\Domain\OrderRepositoryInterface;
use App\Modules\Sales\Infrastructure\EloquentOrderRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Core Database
        $this->app->singleton(UnitOfWorkInterface::class, function ($app) {
            return new UnitOfWork($app['db']);
        });

        // Core Bus
        $this->app->singleton(CommandBus::class);
        $this->app->singleton(QueryBus::class);

        // Identity Module Repositories
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        // Sales Module Repositories
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
