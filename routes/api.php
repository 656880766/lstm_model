<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\locationsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\NoteAverageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LikedLocationsController;
use PhpParser\Parser\Tokens;

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
Route::get('token', function (Request $request) {
    return $request->create_token("yves")->toplaintext();
});
Route::post('user/register', [UsersController::class, 'register'])->name('user.register');
Route::post('user/login', [UsersController::class, 'login'])->name('user.login');
Route::get('user/logout', [UsersController::class, 'logout'])->name('user.logout');
Route::post('locations/create', [locationsController::class, 'create'])->name('locations.create');
Route::post('user/forgotPassword', [UsersController::class, 'forgotPassword'])->middleware('guest')->name('password.sent');
Route::post('user/resetPassword/{token}', [UsersController::class, 'resetPassword($token)'])->middleware('guest')->name('password.reset');
Route::get('user/getReservationsByUser', [UsersController::class, 'getReservationsByUser'])->name('user.getReservationsByUser');
Route::get('user/getUserById', [UsersController::class, 'getUserById'])->name('user.getUserById');
Route::delete('user/delete', [UsersController::class, 'delete'])->name('user.delete');
Route::get('user/getUsers', [UsersController::class, 'getUsers'])->name('Users.getUsers');
Route::post('user/update_avatar', [UsersController::class, 'update_avatar'])->name('user.update_avatar');
Route::post('user/update', [UsersController::class, 'update'])->name('User.update');
Route::post('categories/create', [CategoriesController::class, 'create'])->name('categories.create');
Route::get('categories/getAll', [categoriesController::class, 'getAll'])->name('categories.getAll');
Route::get('locations/getLocationBycategoriesId', [locationsController::class, 'getLocationBycategoriesId'])->name('locations.getLocationBycategoriesId');
Route::get('locations/getLocationsWithCategory', [locationsController::class, 'getLocationsWithCategory'])->name('locations.getLocationsWithCategory');
Route::post('locations/create', [locationsController::class, 'create'])->name('locations.create');

Route::post('locations/update', [locationsController::class, 'update'])->name('locations.update');
Route::delete('locations/delete', [locationsController::class, 'delete'])->name('locations.delete');
Route::get('locations/storeLike', [locationsController::class, 'storeLike'])->name('locations.storeLike');
Route::get('locations/getFavoriteLocation', [locationsController::class, 'getFavoriteLocation']);
Route::get('locations/getNotFavoriteLocation', [locationsController::class, 'getNotFavoriteLocation']);

Route::get('categories/getCategoryWithLocations', [categoriesController::class, 'getCategoryWithLocations'])->name('categories.getCategoryWithLocations');
Route::post('categories/update', [categoriesController::class, 'update'])->name('categories.update');
Route::delete('categories/delete', [categoriesController::class, 'delete'])->name('categories.delete');
Route::get('likedlocations/liker', [LikedLocationsController::class, 'liker'])->name('LikedLocationsController.liker');
Route::post('reserve/reserve', [ReserveController::class, 'reserve'])->name('reserve.reserve');
Route::get('reserve/confirm_reserve', [ReserveController::class, 'confirm_reserve'])->name('reserve.confirm_reserve');


Route::get('reserve/getReservationsByLocation', [ReserveController::class, 'getReservationsByLocation'])->name('reserve.getReservationsByLocation');
Route::delete('reserve/destroy', [ReserveController::class, 'destroy'])->name('reserve.destroy');
Route::get('reserve/showByCustomerId', [ReserveController::class, 'showByCustomerId'])->name('reserve.showByCustomerId');
Route::get('reserve/getAll', [ReserveController::class, 'getAll'])->name('reserve.getAll');
Route::delete('reserve/refuseReserve', [ReserveController::class, 'refuseReserve'])->name('reserve.refuseReserve');
Route::get('reserve/updateStatusForEndReserve', [ReserveController::class, 'updateStatusForEndReserve'])->name('reserve.updateStatusForEndReserve');
Route::get('note/makeNote', [NoteAverageController::class, 'makeNote'])->name('NoteAverage.makeNote');
Route::get('notification/getNotificationsCustomers', [NotificationController::class, 'getNotificationsCustomers'])->name('Notification.getNotificationsCustomers');
Route::get('notification/getNotificationsAdmin', [NotificationController::class, 'getNotificationsAdmin'])->name('Notification.getNotificationsAdmin');
Route::get('notification/readNotification', [NotificationController::class, 'readNotification'])->name('Notification.readNotification');

Route::middleware(['auth:sanctum'])->group(function () {
});









// Route::prefix('v4')->group(function () {


//     Route::get('reserve/confirm_reserve', [ReserveController::class, 'confirm_reserve'])->name('reserve.confirm_reserve');

  
//     Route::get('reserve/showByCustomerId', [ReserveController::class, 'showByCustomerId']);

// });
//routes pour les emplacements aimés


// Route::prefix('v5')->group(function () {

//     Route::resource('likedlocations', LikedlocationsController::class);

//     Route::post('likedlocations/showAll', [LikedlocationsController::class, 'showAll']);
//     Route::post('likedlocations/showBylocationsId', [LikedlocationsController::class, 'showById']);
//     Route::put('likedlocations/update', [LikedlocationsController::class, 'update']);
//     Route::delete('likedlocations/destroy', [LikedlocationsController::class, 'destroy']);
// });
