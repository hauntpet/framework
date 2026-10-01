<?php

use Illuminate\Support\Facades\Route;
use Haunt\Framework\Http\Middleware\IsInstalledMiddleware;

Route::livewire('/install', 'haunt-framework::install.install')
    ->middleware(['web'])
    ->withoutMiddleware([IsInstalledMiddleware::class]);
