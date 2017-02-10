<?php

namespace App\Providers;


use App\Models\{Analysis, Watchlist, WatchlistItem, Company, Notification, NotificationCondition, User};
use App\Policies\{AnalysisPolicy, WatchlistPolicy, WatchlistItemPolicy, CompanyPolicy, NotificationPolicy, NotificationConditionPolicy, AdminPolicy};


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
        Notification::class => NotificationPolicy::class,
        NotificationCondition::class => NotificationConditionPolicy::class,
        User::class => AdminPolicy::class,
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
