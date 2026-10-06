<?php

// routes/api.php mein ye code add karo

use App\Http\Controllers\AIChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('ai')->middleware('api')->group(function () {
    // Customer support
    Route::post('/support', [AIChatController::class, 'support']);
    
    // Coding assistant
    Route::post('/code', [AIChatController::class, 'coding']);
    
    // General chat
    Route::post('/chat', [AIChatController::class, 'chat']);
});
