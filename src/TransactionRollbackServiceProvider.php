<?php

namespace Geow\TransactionRollback;

use Geow\TransactionRollback\Livewire\TransactionRollback;
use Illuminate\Contracts\Foundation\Application;
use Livewire\LivewireManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TransactionRollbackServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('transaction-rollback')
            ->hasViews();
    }

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'transaction-rollbacks');

        $this->callAfterResolving('livewire', function (LivewireManager $livewire, Application $app) {
            $livewire->component('transaction-rollbacks', TransactionRollback::class);
        });
    }
}
