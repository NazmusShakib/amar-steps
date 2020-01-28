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

Route::post('register', 'API\RegisterController@register');
Route::post('login', 'API\RegisterController@login');
Route::get('unauthorized', function () {
    return response()->json([
        'success' => false,
        'message' => 'You are not logged in, e.g. using a valid access token',
    ]);
})->name('api.unauthorized');

/*Route::middleware(['auth:api', 'role:admin|staff|subscriber'])->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::group(['middleware' => ['auth:api']], function () {

    Route::get('profile', 'API\RegisterController@profile');

    Route::apiResource('users', 'API\UserController', ['only' => [
        'index', 'store', 'show', 'update', 'destroy']])->middleware(['role:admin']);

    Route::apiResource('badges', 'API\BadgeController', ['only' => [
        'index', 'store', 'show', 'update', 'destroy']])->middleware(['role:admin']);

    Route::apiResource('activities', 'API\ActivityLogController', ['only' => [
        'index', 'store', 'show', 'update', 'destroy']])->middleware(['role:admin']);

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Hm, why did you land here somehow? If error persists, contact info@website.com'], 404);
});

Route::get('/doc', function () {
    return view('api_documentation');
});
