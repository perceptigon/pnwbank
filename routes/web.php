<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@home');

Route::get('/logout', 'Auth\LoginController@logout');

Auth::routes();

Route::get('/api/deposit/{acct}', 'APIController@deposit');

Route::get('/grants/city', 'GrantController@city');
Route::post('/grants/city', 'GrantController@reqCity');

Route::get("/contact", "HomeController@contact");
Route::post("/contact", "HomeController@contactPost");

Route::get("/grants/entrance", "GrantController@entrance");
Route::post("/grants/entrance", "GrantController@reqEntrance");

Route::get("/grants/oil", "GrantController@oil");
Route::post("/grants/oil", "GrantController@reqOil");

Route::get('/grants/id', 'GrantController@idGrant');
Route::post('/grants/id', 'GrantController@reqIDGrant');

Route::get('/grants/nuke', 'GrantController@nukes');
Route::post('/grants/nuke', 'GrantController@reqNukes');

Route::get('/grants/egr', 'GrantController@egrGrant');
Route::post('/grants/egr', 'GrantController@reqEGRGrant');

Route::get('/grants/cce', 'GrantController@cceGrant');
Route::post('/grants/cce', 'GrantController@reqcceGrant');

Route::get('/grants/nrf', 'GrantController@nrfGrant');
Route::post('/grants/nrf', 'GrantController@reqnrfGrant');

Route::get('/grants/mlp', 'GrantController@mlpGrant');
Route::post('/grants/mlp', 'GrantController@reqmlpGrant');

Route::get('/grants/pb', 'GrantController@pbGrant');
Route::post('/grants/pb', 'GrantController@reqpbGrant');

Route::get('/grants/irondome', 'GrantController@irondomeGrant');
Route::post('/grants/irondome', 'GrantController@reqirondomeGrant');

Route::get('/grants/activity', 'GrantController@activity');
Route::post('/grants/activity', 'GrantController@reqActivity');

Route::get('/loans', 'LoanController@loans');
Route::post('/loans', 'LoanController@reqloan');

Route::get('/lookup/{code}', 'LoanController@lookup');
Route::post('/lookup/{code}', 'LoanController@editLoan');

Route::get('/market', 'MarketController@market');
Route::post('/market', 'MarketController@reqMarket');

Route::get("/admin", "AdminController@index");

Route::get("/admin/users", "AdminController@users");

Route::get("/admin/users/edit/{uID}", "AdminController@editUser");
Route::post("/admin/users/edit/{uID}", "AdminController@postEditUser");

Route::get("/admin/loans", "AdminController@loans");
Route::post("/admin/loans", "AdminController@loanPost");

Route::get("/admin/market", "AdminController@market");
Route::post("/admin/market", "AdminController@marketPost");

Route::get("/admin/taxes", "AdminController@taxes");

Route::get("/admin/so", "AdminController@so");
Route::post("/admin/so", "AdminController@soPost");

Route::get("/admin/city", "AdminController@city");
Route::post("/admin/city", "AdminController@cityPost");

Route::get("/admin/entrance", "AdminController@entrance");
Route::post("/admin/entrance", "AdminController@entrancePost");

Route::get("/admin/activity", "AdminController@activity");
Route::post("/admin/activity", "AdminController@activityPost");

Route::get("/admin/oil", "AdminController@oil");
Route::post("/admin/oil", "AdminController@oilPost");

Route::get("/admin/id", "AdminController@id");
Route::post("/admin/id", "AdminController@idPost");

Route::get("/admin/nukes", "AdminController@nukes");
Route::post("/admin/nukes", "AdminController@nukesPost");

Route::get("/admin/mlp", "AdminController@mlp");
Route::post("/admin/mlp", "AdminController@mlpPost");

Route::get("/admin/egr", "AdminController@egr");
Route::post("/admin/egr", "AdminController@egrPost");

Route::get("/admin/cce", "AdminController@cce");
Route::post("/admin/cce", "AdminController@ccePost");

Route::get("/admin/nrf", "AdminController@nrf");
Route::post("/admin/nrf", "AdminController@nrfPost");

Route::get("/admin/pb", "AdminController@pb");
Route::post("/admin/pb", "AdminController@pbPost");

Route::get("/admin/irondome", "AdminController@irondome");
Route::post("/admin/irondome", "AdminController@irondomePost");

Route::get("/admin/settings", "AdminController@settings");
Route::post("/admin/settings", "AdminController@editSettings");

Route::get("/admin/logs/{category?}", "AdminController@logs");

Route::get("/admin/members", "AdminController@members");
Route::post("/admin/members", "AdminController@membersPOST");
Route::get("/admin/members/{nID}", "AdminController@memberView");
Route::post("/admin/members/{nID}", "AdminController@memberViewPOST");

Route::get("/admin/accounts", "AdminController@accounts");

Route::get("/accounts", "UserController@bankAccounts");
Route::post("/accounts", "UserController@bankAccountsPost");

Route::get("/accounts/{id}", "UserController@viewAccount");
Route::post("/accounts/{id}", "UserController@viewAccountPost");

Route::get("/user/dashboard", "UserController@dashboard");
Route::get("/user/export", "UserController@userExport");

Route::get("/verify/{token?}", "VerifyController@verifyAccount");
Route::post("/verify/{token?}", "VerifyController@verifyAccountPost");

Route::get("/notverified", "VerifyController@notVerified");

Route::get("/signin", 'Defense\MainController@signIn');
Route::post("/signin", 'Defense\MainController@doSignIn');

Route::get("/budget/{days}", 'AdminController@spreadsheet');

Route::get("/budget", 'AdminController@budget');
Route::post("/budget", 'AdminController@postBudget');

Route::get("/defense/dashboard", 'Defense\MainController@dashboard')->middleware("verified");

// start targeting system
Route::get("/defense/targets", 'Defense\MainController@targets');
Route::post("defense/targets", 'Defense\MainController@addTargets');

Route::get("/defense/attackers", 'Defense\MainController@attackers');

Route::get("/defense/attackers/{id}", 'Defense\MainController@attacker');
Route::post("/defense/attackers/{id}", 'Defense\MainController@postAttacker');

Route::get("/defense/defenders", 'Defense\MainController@defenders');

Route::get("/defense/defenders/{id}", 'Defense\MainController@defender');
Route::post("/defense/defenders/{id}", 'Defense\MainController@postDefender');

Route::get("/defense/reset", 'Defense\MainController@reset');

Route::get("/defense/spreadsheet", 'Defense\MainController@spreadsheet');

Route::get("/defense/message", 'Defense\MainController@message');

// end targeting system

//start spy system

Route::get("/defense/spies", 'Defense\SpyController@spies');
Route::post("/defense/spies", 'Defense\SpyController@addSpies');

Route::get("/defense/spyattackers", 'Defense\SpyController@attackers');

Route::get("/defense/spyattackers/{id}", 'Defense\SpyController@attacker');
Route::post("/defense/spyattackers/{id}", 'Defense\SpyController@postAttacker');

Route::get("/defense/spydefenders", 'Defense\SpyController@defenders');

Route::get("/defense/spydefenders/{id}", 'Defense\SpyController@defender');
Route::post("/defense/spydefenders/{id}", 'Defense\SpyController@postDefender');

Route::get("/defense/spyreset", 'Defense\SpyController@reset');

Route::get("/defense/spyspreadsheet", 'Defense\SpyController@spreadsheet');

Route::get("/defense/spymessage", 'Defense\SpyController@message');

Route::get("/defense/nextround", 'Defense\SpyController@nextRound');

Route::get("/defense/spyattackers/results/{id}", 'Defense\SpyController@attackResults');

Route::get("/defense/spydefenders/results/{id}", 'Defense\SpyController@defendResults');

Route::get("/defense/spies/results/submit", 'Defense\SpyController@resultsSubmit');
Route::post("/defense/spies/results/submit", 'Defense\SpyController@postResultsSubmit');

Route::get("/defense/spies/results", 'Defense\SpyController@results');

Route::get("/defense/spies/refresh", 'Defense\SpyController@refresh');

Route::get("/defense/mmr", "AdminController@mmr");
Route::post("/defense/mmr", "AdminController@mmrPOST");

Route::get("/nation/removeInactive/{id}", 'HomeController@removeInactive');


//end spy system
Route::get("/ia", "TibernetController@home");
Route::get("/ia/home", "TibernetController@home");

Route::get("/ia/apply", "TibernetController@apply");
Route::post("/ia/apply", "TibernetController@postApply");

Route::get("/ia/applicants", "TibernetController@applicants");
Route::post("/ia/applicants", "TibernetController@postApplicants");

Route::get("/ia/academy", "TibernetController@academy");
Route::post("/ia/academy", "TibernetController@postAcademy");

Route::get("/ia/track", "TibernetController@track");
Route::post("/ia/track", "TibernetController@postTrack");

Route::get('/ia/notes/{noob}', "TibernetController@notes");
Route::post('/ia/notes/{noob}', "TibernetController@postNotes");

Route::get('/ia/unmask', "TibernetController@unmask");

Route::get('/ia/recruiting', "AdminController@recruiting");
Route::post('/ia/recruiting', "AdminController@recruitingPOST");

Route::get('/admin/income', "AdminController@income");
