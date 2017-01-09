<?php

namespace App\Providers;


use App\Models\{Analysis, Watchlist, WatchlistItem, Company};
use App\Policies\{AnalysisPolicy, WatchlistPolicy, WatchlistItemPolicy, CompanyPolicy};


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Analysis::class => AnalysisPolicy::class,
        Watchlist::class => WatchlistPolicy::class,
        WatchlistItem::class => WatchlistItemPolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
