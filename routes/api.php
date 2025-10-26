
<?php

use App\Http\Controllers\DesencriptarController;
use Illuminate\Support\Facades\Route;

Route::post("/decrypt", [DesencriptarController::class, 'decrypt'])->name('decrypt');

Route::get('/test', function () {
    return response()->json(['status' => 'API activa']);
});
