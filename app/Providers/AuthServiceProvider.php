<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\LeaveRequest;
use App\Policies\DocumentPolicy;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        LeaveRequest::class => LeaveRequestPolicy::class,
        Document::class => DocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}