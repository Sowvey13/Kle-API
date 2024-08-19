<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Trespass;


Route::post("/", [AuthController::class, "login"])->name("login");
Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware("auth:sanctum")->group(function () {
Route::get('/index', [AuthController::class, 'index'])->name('index');
Route::get('/create/show', [ProductController::class, 'createShow']);
Route::post("/products/create", [ProductController::class, "create"])->name("products.create");
Route::get('/products/product_detail/{id}', [ProductController::class, 'ProductDetail'])->name('products.product_detail');
Route::get("/products", [ProductController::class, "productsAll"])->name("products.store");
Route::put("/products/update/{id}", [ProductController::class, "update"])->name("products.update");
Route::get("/products/show/{id}", [ProductController::class, "show"])->name("products.show");
Route::delete("/products/delete/{id}", [ProductController::class, "destroy"])->name("products.destroy");
});

Route::get('login_error', function () {
    return view('login_error');
})->name('login_error');