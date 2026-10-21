<?php

use Haunt\Framework\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::fallback(PageController::class);
