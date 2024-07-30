<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\GameController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\FootballGameController;
use App\Http\Controllers\admin\TennisGameController;
use App\Http\Controllers\admin\ZapiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('game-score-list/{game_id?}','AdminController@getGameScoreList');


//Cricket Game API
Route::get('cricket/game-list','GameController@getGameListApi')->name('cricket.game.list.api');
Route::get('cricket/match-list/{game_id?}','GameController@getMatchListApi')->name('cricket.match.list.api');
Route::get('cricket/game/single/{game_id?}','GameController@getSingleGameByIdApi')->name('cricket.game.single.api');
Route::post('cricket/game/place-bet','GameController@placeBetForCricketApi')->name('cricket.game.place.bet.api');
Route::get('cricket/match/get-result','GameController@getCricketMatchResultApi')->name('cricket.match.get.result.api');
Route::get('cricket/game/scorecard/{id}',[ZapiController::class,'apiGetScoreCard']);

//Football Game API
Route::get('football/game-list','FootballGameController@getGameListApi')->name('football.game.list.api');
Route::get('football/match-list/{game_id?}','FootballGameController@getMatchListApi')->name('football.match.list.api');
Route::get('football/game/single/{game_id?}','FootballGameController@getSingleGameByIdApi')->name('football.game.single.api');
Route::post('football/game/place-bet','FootballGameController@placeBetForFootballApi')->name('football.game.place.bet.api');
Route::get('football/match/get-result','FootballGameController@getFootballMatchResultApi')->name('football.match.get.result.api');
Route::get('football/game/scorecard/{id}',[ZapiController::class,'apiGetFootballScore']);

//Tennis Game API
Route::get('tennis/game-list','TennisGameController@getGameListApi')->name('tennis.game.list.api');
Route::get('tennis/match-list/{game_id?}','TennisGameController@getMatchListApi')->name('tennis.match.list.api');
Route::get('tennis/game/single/{game_id?}','TennisGameController@getSingleGameByIdApi')->name('tennis.game.single.api');
Route::post('tennis/game/place-bet','TennisGameController@placeBetForTennisApi')->name('tennis.game.place.bet.api');
Route::get('tennis/match/get-result','TennisGameController@getTennisMatchResultApi')->name('tennis.match.get.result.api');

//Horse Racing Game API
Route::get('horseracing/game-list','HorseRacingGameController@getGameListApi')->name('horseracing.game.list.api');
Route::get('horseracing/match-list/{game_id?}','HorseRacingGameController@getMatchListApi')->name('horseracing.match.list.api');
Route::get('horseracing/game/single/{time_slot_id?}','HorseRacingGameController@getSingleGameByIdApi')->name('horseracing.game.single.api');
Route::post('horseracing/game/place-bet','HorseRacingGameController@placeBetForHorseRacingApi')->name('horseracing.game.place.bet.api');
Route::get('horseracing/match/get-result','HorseRacingGameController@getHorseRacingMatchResultApi')->name('horseracing.match.get.result.api');

//Greyhound Racing Game API
Route::get('greyhoundracing/game-list','GreyhoundRacingGameController@getGameListApi')->name('greyhoundracing.game.list.api');
Route::get('greyhoundracing/match-list/{game_id?}','GreyhoundRacingGameController@getMatchListApi')->name('greyhoundracing.match.list.api');
Route::get('greyhoundracing/game/single/{time_slot_id?}','GreyhoundRacingGameController@getSingleGameByIdApi')->name('greyhoundracing.game.single.api');
Route::post('greyhoundracing/game/place-bet','GreyhoundRacingGameController@placeBetForGreyhoundRacingApi')->name('greyhoundracing.game.place.bet.api');
Route::get('greyhoundracing/match/get-result','GreyhoundRacingGameController@getGreyhoundRacingMatchResultApi')->name('greyhoundracing.match.get.result.api');

Route::get('get-all-games-list','GameController@getAllGamesListApi')->name('get.all.games.list.api');

