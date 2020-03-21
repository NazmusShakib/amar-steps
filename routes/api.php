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

Route::post('register', 'RegisterController@register');
Route::post('login', 'RegisterController@login');

Route::get('phone/verify', 'PhoneVerificationController@show')->name('phoneVerification.notice');
Route::post('phone/verify', 'PhoneVerificationController@verify')->name('phoneVerification.verify');

Route::post('build-twiml/{code}', 'PhoneVerificationController@buildTwiMl')->name('phoneVerification.build');

Route::get('unauthorized', function () {
    return response()->json([
        'success' => false,
        'message' => 'You are not logged in, e.g. using a valid access token',
    ]);
})->name('api.unauthorized');

/*Route::middleware(['auth:api', 'role:admin|staff|subscriber'])->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::group(['middleware' => ['auth:api', 'verifiedPhone']], function () {

    Route::get('profile', 'RegisterController@profile');
    Route::post('profile', 'RegisterController@updateProfile');
    Route::post('profile/change-password', 'RegisterController@changePassword');


    Route::apiResource('users', 'UserController', ['only' => [
        'index', 'store', 'show', 'update', 'destroy']])->middleware(['role:admin']);

    Route::post('badges/update/{id}', 'BadgeController@update');
    Route::apiResource('badges', 'BadgeController', ['only' => [
        'index', 'store', 'show', 'destroy']])->middleware(['role:admin|staff|subscriber']);

    Route::get('activities/badges', 'ActivityLogController@activityBadge');
    Route::apiResource('activities', 'ActivityLogController', ['only' => [
        'index', 'store', 'show', 'update', 'destroy']])->middleware(['role:admin|staff|subscriber']);

    # Handel friends request
    Route::get('friends', 'FriendshipsController@friendsList');
    Route::post('friends/send-request/{id}', 'FriendshipsController@sendFriendRequest');
    Route::post('friends/accept-request/{id}', 'FriendshipsController@acceptFriendRequest');
    Route::post('friends/deny-request/{id}', 'FriendshipsController@denyFriendRequest');
    Route::post('friends/un-friend/{id}', 'FriendshipsController@unFriend');
    Route::get('friends/pending-requests', 'FriendshipsController@pendingRequests');

    # Handel followers request
    Route::get('followers', 'BeFollowerController@followingList');
    Route::post('followers/send-follow-request/{id}', 'BeFollowerController@sendFollowRequest');
    Route::post('followers/accept-follow-request/{id}', 'BeFollowerController@acceptFollowRequest');
    Route::post('followers/cancel-follow-request/{id}', 'BeFollowerController@cancelFollowRequest');
    Route::post('followers/decline-follow-request/{id}', 'BeFollowerController@declineFollowRequest');

    # Handel leader board request
    Route::get('leaderboard/auth-rank', 'LeaderBoardController@authRank');
    Route::get('leaderboard', 'LeaderBoardController@leaderBoard');

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Hm, why did you land here somehow? If error persists, contact info@website.com'], 404);
});

Route::get('/doc', function () {
    return view('api_documentation');
});
