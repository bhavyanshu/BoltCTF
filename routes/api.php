<?php

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

/*=============================================>>>>>
= API version 1.0 Routes : Simple Token based auth =
===============================================>>>>>*/
Route::group(['prefix' => 'v1', 'middleware' => ['auth:api','orgprotect']], function () {

  Route::post('/users/search', 'Auth\Roles\OrgAPIController@searchUser');
  Route::post('/users/edit', 'Auth\Roles\OrgAPIController@editUser');
  Route::post('/users/delete', 'Auth\Roles\OrgAPIController@deleteUser');

  Route::post('/addcategory', 'Auth\Roles\OrgAPIController@addCategory');
  Route::post('/category/edit', 'Auth\Roles\OrgAPIController@updateCategory');
  Route::post('/reordercategory', 'Auth\Roles\OrgAPIController@reorderCategory');

  Route::get('/challenges/{catguid}', 'Auth\Roles\OrgAPIController@getChallenges');
  Route::post('/addchallenge', 'Auth\Roles\OrgAPIController@addChallenge');
  Route::post('/challenge/edit', 'Auth\Roles\OrgAPIController@updateChallenge');
  Route::post('/reorderchallenge', 'Auth\Roles\OrgAPIController@reorderChallenge');

  Route::get('/challenge/{chaguid}', 'Auth\Roles\OrgAPIController@getChallenge');
  Route::get('/challenge/{chaguid}/qa', 'Auth\Roles\OrgAPIController@getQAs');

  Route::post('/qa/save', 'Auth\Roles\OrgAPIController@saveQA');
  Route::post('/qa/update', 'Auth\Roles\OrgAPIController@updateQA');
  Route::post('/reorderqa', 'Auth\Roles\OrgAPIController@reorderQAs');

  Route::post('/fileupload', 'Auth\Roles\OrgAPIController@fileUpload');
  Route::post('/challenge/file/delete', 'Auth\Roles\OrgAPIController@fileDelete');
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {

  Route::get('/events', 'Auth\Roles\CommonAPIController@getEvents');
  Route::get('/event/{eguid}/categories', 'Auth\Roles\CommonAPIController@getCategories');
  Route::get('/user', 'Auth\Roles\CommonAPIController@getUser');
  Route::get('/challenges/{catguid}', 'Auth\Roles\CommonAPIController@getChallenges');
  Route::get('/challenge/{chaguid}/files', 'Auth\Roles\CommonAPIController@getChallengeFiles');

  Route::get('/event/{eguid}/leaderboard/{limit?}', 'Auth\Roles\CommonAPIController@getLeaderboardbyEvent');

});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api', 'playerprotect']], function () {

  Route::get('/stadium/event/{eguid}/categories', 'Auth\Roles\PlayerAPIController@getCategories');
  Route::get('/stadium/challenge/qas/{chaguid}', 'Auth\Roles\PlayerAPIController@getQuestions');
  Route::post('/stadium/challenge/submit_answer', 'Auth\Roles\PlayerAPIController@submitAnswer');

});
