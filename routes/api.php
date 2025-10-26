
<?php

use App\Http\Controllers\CryptoController;
use Illuminate\Support\Facades\Route;

Route::post("/decrypt", [CryptoController::class, 'decrypt'])->name('decrypt');

Route::get('/test', function () {
    return response()->json(['status' => 'API activa']);
});
