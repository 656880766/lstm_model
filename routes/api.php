<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\LikedlocationsController;
use App\Http\Controllers\locationsController;

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
Route::prefix('v1')->group(function () {
    Route::post('customer/create', [CustomersController::class, 'create']);
    Route::post('customer/showAll', [CustomersController::class, 'showAll']);
    Route::post('customer/showById', [CustomersController::class, 'showById']);
    Route::put('customer/updateById', [CustomersController::class, 'updateById']);
    Route::delete('customer/deleteById', [CustomersController::class, 'deleteById']);
    Route::delete('customer/destroy', [CustomersController::class, 'destroy']);
    Route::post('customer/update_avatar', [CustomersController::class, 'update_avatar']);
});

// routes concernant les emplacements
Route::prefix('v2')->group(function () {

    Route::post('locations/create', [locationsController::class, 'create']);
    Route::get('locations/showAll', [locationsController::class, 'showAll']);
    Route::post('locations/showBycategoriesId', [locationsController::class, 'showBycategoriesId']);
    Route::put('locations/updateById', [locationsController::class, 'updateById']);
    Route::delete('locations/destroy', [locationsController::class, 'destroy']);
});

//routes concernant les categories
// Route::prefix('v3')->group(function () {

//     Route::resource('categories', categoriesController::class);
//     Route::post('categories/create', [categoriesController::class, 'create']);
//     Route::post('categories/showAll', [categoriesController::class, 'showAll']);
//     Route::post('categories/showById', [categoriesController::class, 'showById']);
//     Route::put('categories/update', [categoriesController::class, 'update']);
//     Route::delete('categories/destroy', [categoriesController::class, 'destroy']);
// });

//routes concernant les categories
// Route::prefix('v4')->group(function () {

//     Route::resource('reserve', ReserveController::class);
//     Route::post('reserve/create', [ReserveController::class, 'create']);
//     Route::post('reserve/showAll', [ReserveController::class, 'showAll']);
//     Route::post('reserve/showById', [ReserveController::class, 'showById']);
//     Route::put('reserve/update', [ReserveController::class, 'update']);
//     Route::delete('reserve/destroy', [ReserveController::class, 'destroy']);
// });
//routes pour les emplacements aimés


// Route::prefix('v5')->group(function () {

//     Route::resource('likedlocations', LikedlocationsController::class);
//     Route::post('likedlocations/create', [LikedlocationsController::class, 'create']);
//     Route::post('likedlocations/showAll', [LikedlocationsController::class, 'showAll']);
//     Route::post('likedlocations/showBylocationsId', [LikedlocationsController::class, 'showById']);
//     Route::put('likedlocations/update', [LikedlocationsController::class, 'update']);
//     Route::delete('likedlocations/destroy', [LikedlocationsController::class, 'destroy']);
// });
