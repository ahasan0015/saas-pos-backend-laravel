<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 🔓 পাবলিক রুট (লগইন করার জন্য টোকেন লাগবে না)
Route::post('/login', [AuthController::class, 'login']);

// 🔒 প্রটেক্টেড রুটস (এই রুটগুলো অ্যাক্সেস করতে Bearer Token লাগবে)
Route::middleware('auth:sanctum')->group(function () {
    
    // লগআউট রুট
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // কারেন্ট লগইন থাকা ইউজারের ডাটা দেখার জন্য টেস্ট রুট
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});