<?php
// routes/api.php
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [ProductController::class, 'search']);

?>