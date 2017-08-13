<?php

/*=============================================>>>>>
= Routes without auth =
===============================================>>>>>*/
Route::group(['middleware' => ['web']], function () {
  Route::get('user/verify/{confirmcode}','Auth\RegisterController@verifyEmail');
});

Route::group(['middleware' => ['web','guest']], function () {
  Route::get('/', [
    'as'=>'welcome',
    'uses' => 'Home\HomeController@getSiteLandingPage'
  ]);

  Route::get('/about', function()
  {
    return view('non-auth.about');
  });

  // Login & Registration routes
  Route::post('register', 'Auth\RegisterController@register');
  Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
  Route::post('login', 'Auth\LoginController@login');

  // Password Reset Routes...
  Route::get('user/password/reset', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
  Route::post('user/password/email', ['as' => 'password.email', 'uses' => 'Auth\ResetPasswordController@sendResetLinkEmail']);
  Route::get('user/password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
  Route::post('user/password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);
});

/*=============================================>>>>>
= Routes require auth =
===============================================>>>>>*/
Route::group(['middleware' => ['web','auth']], function () {
  Route::get('/logout', [
    'as'=>'logout',
    'uses' => 'Auth\LoginController@logout'
  ]);
});

/*=============================================>>>>>
= Routes require auth -> Block status check -> App =
===============================================>>>>>*/
Route::group(['middleware' => ['web','auth','blockcheck']], function () {
  Route::get('dashboard', [
    'as'=>'dashboard',
    'uses' => 'Home\HomeController@index'
  ]);

  //Profiles related routes
  Route::post('user/profile/upload', ['as' => 'userprofilepic_upload', 'uses' => 'Universal\ProfileController@profpicupload']);
  Route::get('user/profile/edit','Universal\ProfileController@editform');
  Route::post('user/profile/edit', array('as'=>'userprofile_update','uses'=>'Universal\ProfileController@saveProfileinfo'));

  //Security settings related routes
  Route::get('user/settings/password', array('as'=>'password_change', 'uses' => 'Universal\ProfileController@showPasschangeform'));
  Route::post('user/settings/password','Universal\ProfileController@passwordReset');

  Route::get('/challenge/file/{token}', 'Universal\FileController@fileDownload');
});

/*=============================================================================>>>>>
= ROUTES REQUIRE AUTH -> BLOCKED STATUS CHECK -> ORGANIZER ROLE CHECK -> APP =
=============================================================================>>>>>*/
Route::group(['middleware' => ['web','auth','blockcheck','orgprotect']], function () {
  //Event related routes
  Route::get('event/new/register','Auth\Roles\OrgController@registerEvent');
  Route::get('event/edit/{eguid}','Auth\Roles\OrgController@editEvent');
  Route::get('event/{eguid}/categories','Auth\Roles\OrgController@editCategory');
  Route::post('event/post/update', array('as'=>'event_register','uses'=>'Auth\Roles\OrgController@saveEvent'));
  Route::get('event/view','Auth\Roles\OrgController@listEvents');

  //Challenge related routes
  Route::get('challenge/{chaguid}','Auth\Roles\OrgController@editChallenge');

  //User Manager
  Route::get('user/manager', 'Auth\Roles\OrgController@getUserManager');
});

/*=============================================================================>>>>>
= ROUTES REQUIRE AUTH -> BLOCKED STATUS CHECK -> PLAYER ROLE CHECK -> APP =
=============================================================================>>>>>*/
Route::group(['middleware' => ['web','auth','blockcheck','playerprotect']], function () {
  //Stadium Features
  Route::get('/stadium/event/{eguid}','Auth\Roles\PlayerController@getEvent');
  Route::get('/stadium/event/{eguid}/challenge/{chaguid}', 'Auth\Roles\PlayerController@getChallenge');
  Route::get('/stadium/event/{eguid}/leaderboard', 'Auth\Roles\PlayerController@getLeaderboard');
  Route::get('/stadium/event/{eguid}/report', 'Auth\Roles\PlayerController@getReport');
});
