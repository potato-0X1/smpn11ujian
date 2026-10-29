<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Exam;
use App\Models\Student;
use App\Observers\SchoolActivityObserver;

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
        Exam::observe(SchoolActivityObserver::class);
        Student::observe(SchoolActivityObserver::class);
    }
}
