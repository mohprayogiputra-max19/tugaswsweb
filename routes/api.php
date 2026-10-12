<?php

use App\Http\Controllers\Api\SanctumAuthController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [SanctumAuthController::class, 'login'])
    ->middleware('throttle:6,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => (int) ($user->role === 'admin'),
            'email_verified_at' => $user->email_verified_at,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    });

    Route::post('/logout', [SanctumAuthController::class, 'logout']);
    Route::post('/logout-all', [SanctumAuthController::class, 'logoutAll']);
});

Route::apiResource('books', BookController::class);
