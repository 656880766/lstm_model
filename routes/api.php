<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\LikedlocationsController;
use App\Http\Controllers\locationsController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * 
 * routes concernant le client
 */
Route::middleware(['auth:sanctum'])->group(function () {
});
Route::prefix('v1')->group(function () {
    Route::post('customer/create', [CustomersController::class, 'create'])->name('customer.create');
    Route::post('customer/showAll', [CustomersController::class, 'showAll'])->name('customer.showAll');
    Route::post('customer/showById', [CustomersController::class, 'showById'])->name('customer.showById');
    Route::put('customer/updateById', [CustomersController::class, 'updateById'])->name('customer.updateById');
    Route::delete('customer/deleteById', [CustomersController::class, 'deleteById'])->name('customer.deleteById');
    Route::delete('customer/destroy', [CustomersController::class, 'destroy'])->name('customer.destroy');
    Route::post('customer/update_avatar', [CustomersController::class, 'update_avatar'])->name('customer.update_avatar');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// routes concernant les emplacements
Route::prefix('v2')->group(function () {

    Route::post('locations/create', [locationsController::class, 'create']);
    Route::get('locations/showAll', [locationsController::class, 'showAll']);
    Route::post('locations/showBycategoriesId', [locationsController::class, 'showBycategoriesId']);
    // Route::put('locations/updateById', [locationsController::class, 'updateById']);
    Route::delete('locations/deleteById', [locationsController::class, 'deleteById']);
});

//routes concernant les categories
Route::prefix('v3')->group(function () {

    Route::post('categories/create', [CategoriesController::class, 'create']);
    Route::get('categories/showAll', [categoriesController::class, 'showAll']);
    Route::get('categories/showById', [categoriesController::class, 'showById']);
    Route::put('categories/updateById', [categoriesController::class, 'updateById']);
    Route::delete('categories/deleteById', [categoriesController::class, 'deleteById']);
});

///routes concernant les categories
Route::prefix('v4')->group(function () {

    Route::get('reserve/create', [ReserveController::class, 'create'])->name('reserve.create');
    Route::get('reserve/confirm_reserve', [ReserveController::class, 'confirm_reserve'])->name('reserve.confirm_reserve');

    Route::get('reserve/showAll', [ReserveController::class, 'showAll']);
    Route::get('reserve/showByCustomerId', [ReserveController::class, 'showByCustomerId']);
    Route::delete('reserve/deleteById', [ReserveController::class, 'deleteById']);
});
//routes pour les emplacements aimés


// Route::prefix('v5')->group(function () {

//     Route::resource('likedlocations', LikedlocationsController::class);
//     Route::post('likedlocations/create', [LikedlocationsController::class, 'create']);
//     Route::post('likedlocations/showAll', [LikedlocationsController::class, 'showAll']);
//     Route::post('likedlocations/showBylocationsId', [LikedlocationsController::class, 'showById']);
//     Route::put('likedlocations/update', [LikedlocationsController::class, 'update']);
//     Route::delete('likedlocations/destroy', [LikedlocationsController::class, 'destroy']);
// });
