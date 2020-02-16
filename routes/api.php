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

// TODO update the trading API
// Trading API
Route::get("/api/gInfo/{gID}", "GrantController@gInfo");
Route::get("/api/memberID/{nID}", "APIController@memberID");
Route::get("/api/trading/{nID}/{resource}", "APIController@trading");
Route::get("/api/tradeTracker/{nID}/{resource}", "APIController@TradeTracker");
/*Route::get("/api/nation/{nID}", function ($nID) {
    $nation = \json_decode(file_get_contents("https://politicsandwar.com/api/nation/id={$nID}&key=".env("PW_API_KEY")), true);
    echo json_encode($nation);
});*/

// Loan API
Route::get("/api/v1/loan/{code}", "LoanController@getLoan");
Route::post("/api/v1/loan", "LoanController@reqLoan");

// City Grant API
Route::post("/api/v1/city", "GrantController@reqCity");

// Member's API
Route::get("/api/v1/members", "MembersController@members");

// Defense API
Route::post("/api/v1/signin", "DefenseController@signin");
