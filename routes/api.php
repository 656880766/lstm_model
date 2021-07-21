<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\CustomerController;
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

    Route::resource('customer', CustomerController::class);
    Route::post('customer/create', [CustomerController::class, 'create']);
    Route::post('customer/showAll', [CustomerController::class, 'showAll']);
    Route::post('customer/showById', [CustomerController::class, 'showById']);
    Route::put('customer/update', [CustomerController::class, 'update']);
    Route::put('customer/delete', [CustomerController::class, 'update']);
    Route::delete('customer/destroy', [CustomerController::class, 'destroy']);
});

// routes concernant les emplacements
Route::prefix('v2')->group(function () {

    Route::resource('locations', locationsController::class);
    Route::post('locations/create', [locationsController::class, 'create']);
    Route::post('locations/showAll', [locationsController::class, 'showAll']);
    Route::post('locations/showBycategoriesId', [locationsController::class, 'showBycategoriesId']);
    Route::put('locations/update', [locationsController::class, 'update']);
    Route::delete('locations/destroy', [locationsController::class, 'destroy']);
});

//routes concernant les categories
Route::prefix('v3')->group(function () {

    Route::resource('categories', categoriesController::class);
    Route::post('categories/create', [categoriesController::class, 'create']);
    Route::post('categories/showAll', [categoriesController::class, 'showAll']);
    Route::post('categories/showById', [categoriesController::class, 'showById']);
    Route::put('categories/update', [categoriesController::class, 'update']);
    Route::delete('categories/destroy', [categoriesController::class, 'destroy']);
});

//routes concernant les categories
Route::prefix('v4')->group(function () {

    Route::resource('reserve', ReserveController::class);
    Route::post('reserve/create', [ReserveController::class, 'create']);
    Route::post('reserve/showAll', [ReserveController::class, 'showAll']);
    Route::post('reserve/showById', [ReserveController::class, 'showById']);
    Route::put('reserve/update', [ReserveController::class, 'update']);
    Route::delete('reserve/destroy', [ReserveController::class, 'destroy']);
});
//routes pour les emplacements aimés


Route::prefix('v5')->group(function () {

    Route::resource('likedlocations', LikedlocationsController::class);
    Route::post('likedlocations/create', [LikedlocationsController::class, 'create']);
    Route::post('likedlocations/showAll', [LikedlocationsController::class, 'showAll']);
    Route::post('likedlocations/showBylocationsId', [LikedlocationsController::class, 'showById']);
    Route::put('likedlocations/update', [LikedlocationsController::class, 'update']);
    Route::delete('likedlocations/destroy', [LikedlocationsController::class, 'destroy']);
});
