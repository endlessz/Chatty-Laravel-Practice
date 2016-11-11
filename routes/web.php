<?php

/**
 * Home
 */

Route::get('/', [
	'uses' => 'HomeController@index',
	'as' => 'home',
]);

/**
 * Authentication
 */

Route::get('/signup', [
	'uses' => 'AuthController@getSignUp',
	'as' => 'auth.signup',
	'middleware' => ['guest'],
]);

Route::post('/signup', [
	'uses' => 'AuthController@postSignUp',
	'middleware' => ['guest'],
]);


Route::get('/signin', [
	'uses' => 'AuthController@getSignIn',
	'as' => 'auth.signin',
	'middleware' => ['guest'],
]);


Route::post('/signin', [
	'uses' => 'AuthController@postSignIn',
	'middleware' => ['guest'],
]);

Route::get('/signout', [
	'uses' => 'AuthController@getSignOut',
	'as' => 'auth.signout',
]);

/**
 * Search
 */

Route::get('/search', [
	'uses' => 'SearchController@getResults',
	'as' => 'search.results',
]);


/**
 * Profile
 */

Route::get('/user/{username}', [
	'uses' => 'ProfileController@getProfile',
	'as' => 'profile.index',
]);

Route::get('/profile/edit', [
	'uses' => 'ProfileController@getEdit',
	'as' => 'profile.edit',
	'middleware' => ['auth'],
]);

Route::post('/profile/edit', [
	'uses' => 'ProfileController@postEdit',
	'middleware' => ['auth'],
]);

/**
 * Friends
 */

Route::get('/friends', [
	'uses' => 'FriendController@getIndex',
	'as' => 'friends.index',
	'middleware' => ['auth'],
]);

Route::get('/friends/add/{username}', [
	'uses' => 'FriendController@getAdd',
	'as' => 'friends.add',
	'middleware' => ['auth'],
]);

Route::get('/friends/accept/{username}', [
	'uses' => 'FriendController@getAccept',
	'as' => 'friends.accept',
	'middleware' => ['auth'],
]);

/**
 * Status
 */

Route::post('/status', [
	'uses' => 'StatusController@postStatus',
	'as' => 'status.post',
	'middleware' => ['auth'],
]);

Route::post('/status/{id}/reply', [
	'uses' => 'StatusController@postReply',
	'as' => 'status.reply',
	'middleware' => ['auth'],
]);

Route::get('/status/{id}/like', [
	'uses' => 'StatusController@getLike',
	'as' => 'status.like',
	'middleware' => ['auth'],
]);




