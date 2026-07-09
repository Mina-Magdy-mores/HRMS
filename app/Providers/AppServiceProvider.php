<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            Paginator::useBootstrapFive();
            
            view()->composer('admin.includes.navbar', function ($view) {
                if (auth()->guard('admin')->check()) {
                    $company_id = auth()->guard('admin')->user()->company_id;
                    
                    $pendingRequests = [];
                    $pendingRequestsCount = 0;
                    
                    if (\Illuminate\Support\Facades\Schema::hasTable('employee_requests')) {
                        $pendingRequests = \App\Models\EmployeeRequest::with(['employee', 'type'])
                            ->where('company_id', $company_id)
                            ->where('status', 0)
                            ->orderBy('id', 'desc')
                            ->limit(5)
                            ->get();
                            
                        $pendingRequestsCount = \App\Models\EmployeeRequest::where('company_id', $company_id)
                            ->where('status', 0)
                            ->count();
                    }
                    
                    $view->with(compact('pendingRequests', 'pendingRequestsCount'));
                }
            });
    }
}
