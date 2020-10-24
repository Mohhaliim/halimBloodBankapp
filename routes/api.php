<?php





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

//use Illuminate\Routing\Route;

use App\Http\Controllers\Api\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::get('governorates', 'MainController@governorates');
    Route::get('blood_types', 'MainController@blood_types');
    Route::get('categories', 'MainController@categories');
    Route::get('cities', 'MainController@cities');
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('contactus', 'AuthController@contactUs');
    Route::post('passwordreset', 'AuthController@passwordReset');
    Route::post('newpassword', 'AuthController@newPassword');


    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('settings', 'MainController@settings');
        Route::get('search', 'MainController@search');
        Route::get('favourites', 'MainController@favourites');
        Route::get('posts', 'MainController@posts');
        Route::get('notifications', 'MainController@notifications');
        Route::post('editprofile', 'AuthController@editProfile'); //use user
        Route::post('updatenotifications', 'MainController@updateNotifications');
        Route::post('favouritepost', 'MainController@favouritePost');
        Route::post('createdonation', 'AuthController@createDonation');
        Route::post('donationsearch','MainController@donationSearch');
    });
});
