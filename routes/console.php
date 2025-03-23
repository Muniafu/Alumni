<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clear all caches
Artisan::command('clear-all', function () {
    $this->call('config:clear');
    $this->call('cache:clear');
    $this->call('view:clear');
    $this->call('route:clear');
    $this->info('All caches cleared successfully!');
})->purpose('Clear all application caches');

// Database status check
Artisan::command('db:status', function () {
    try {
        DB::connection()->getPdo();
        $this->info('Database connection: SUCCESS');
        $this->info('Database name: ' . DB::connection()->getDatabaseName());
    } catch (\Exception $e) {
        $this->error('Database connection: FAILED');
        $this->error('Error: ' . $e->getMessage());
    }
})->purpose('Check database connection status');

// List all routes with middleware
Artisan::command('routes:middleware', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'middleware' => implode(', ', $route->middleware())
        ];
    });
    
    $this->table(['Method', 'URI', 'Middleware'], $routes->toArray());
})->purpose('Display all routes with their middleware');
