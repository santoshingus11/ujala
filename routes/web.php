<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BetTicketController;
use App\Http\Controllers\admin\ExposureController;
use App\Http\Controllers\admin\LoginSubmitController;
use App\Http\Controllers\admin\PositionController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\TransferController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\AgentManagementController;
use App\Http\Controllers\admin\BetController;
use App\Http\Controllers\admin\CashbankingController;
use App\Http\Controllers\admin\ExtraController;
use App\Http\Controllers\admin\MarketSettingsController;
use App\Http\Controllers\admin\PlayerLogReportController;
use App\Http\Controllers\admin\GameController;
use App\Http\Controllers\admin\FootballGameController;
use App\Http\Controllers\admin\TennisGameController;
use App\Http\Controllers\admin\HorseRacingGameController;
use App\Http\Controllers\admin\GreyhoundRacingGameController;
use App\Http\Controllers\admin\ZapiController;


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


Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});


Route::get('/cc', function () {
    Artisan::call('cache:clear');
    echo '<script>alert("cache clear Success")</script>';
});


Route::get('/ccc', function () {
    Artisan::call('config:cache');
    echo '<script>alert("config cache Success")</script>';
});
Route::get('/vc', function () {
    Artisan::call('view:clear');
    echo '<script>alert("view clear Success")</script>';
});
Route::get('/cr', function () {
    Artisan::call('route:cache');
    echo '<script>alert("route clear Success")</script>';
});
Route::get('/coc', function () {
    Artisan::call('config:clear');
    echo '<script>alert("config clear Success")</script>';
});

Route::get('store/cricket/series/api',[ZapiController::class,'storeSeriesGames']);
Route::get('store/cricket/games/api',[ZapiController::class,'storeCricketGames']);
Route::get('get/single/match/info/',[ZapiController::class,'liveScoreCard']);
Route::get('check/live/cricket/match/started',[ZapiController::class,'checkLiveMatchStarted']);

Route::get('store/football/games/api',[ZapiController::class,'storeFootballGames']);
Route::get('check/live/football/match/started',[ZapiController::class,'checkLiveMatchStartedfootball']);


Route::get('/', [ClientController::class, 'client_login'])->name('login');
Route::post('client-submit', [ClientController::class, 'login_submit'])->name('login_submit');
Route::middleware('client.login.check')->group(function () {
    
    Route::get('client',[ClientController::class,'client'])->name('client-home');
    Route::get('logout',[ClientController::class,'logout'])->name('client-logout');
    
    Route::get('cricket-details4',[HomeController::class,'details4'])->name('Cricket-details-pages-4');
    Route::get('cricket-details2',[HomeController::class,'details2'])->name('Cricket-details-pages-2');
    Route::get('cricket-details3',[HomeController::class,'details3'])->name('Cricket-details-pages-3');
    Route::get('cricket-details',[HomeController::class,'details'])->name('Cricket-details');
    Route::get('football', [HomeController::class, 'foot_ball'])->name('football-frontend');
    Route::get('tennis', [HomeController::class, 'ten_nis'])->name('tennis-frontend');
    Route::get('cricket', [HomeController::class, 'cric_ket'])->name('cricket-frontend');
    Route::get('table-tenis', [HomeController::class, 'tenis'])->name('table-tenis-frontend');
    Route::get('darts', [HomeController::class, 'dart'])->name('darts-frontend');
    Route::get('badminton', [HomeController::class, 'badmint_on'])->name('badminton-frontend');
    Route::get('kabaddi', [HomeController::class, 'kaba_ddi'])->name('kabaddi-frontend');
    Route::get('boxing', [HomeController::class, 'boxi_ng'])->name('boxing-frontend');
    Route::get('arts', [HomeController::class, 'artss'])->name('arts-frontend');
    Route::get('motor', [HomeController::class, 'motor_sport'])->name('Motor-Sport-frontend');
    Route::get('basketball', [HomeController::class, 'basketball'])->name('basketball-frontend');
    Route::get('election', [HomeController::class, 'election'])->name('election2023-frontend');
    Route::get('icc', [HomeController::class, 'icc'])->name('icc2023-frontend');
    Route::get('lottery', [HomeController::class, 'lottery'])->name('lottery-frontend');
    Route::get('casino', [HomeController::class, 'casino'])->name('live-casino-frontend');
    Route::get('casino_result', [HomeController::class, 'casino_result'])->name('casino-results');
    Route::get('tiger_result', [HomeController::class, 'tiger_result'])->name('tiger-result');
    Route::get('queen_result', [HomeController::class, 'queen_result'])->name('queen-result');
    Route::get('andarbahar_result', [HomeController::class, 'andarbahar_result'])->name('andarbahar-result');
    Route::get('home',[HomeController::class,'home'])->name('home');
    Route::get('mybets',[HomeController::class,'mybets'])->name('my-bets');
    Route::get('secureauth',[HomeController::class,'secureauth'])->name('secureauth');
    Route::get('message',[HomeController::class,'message'])->name('message');
    Route::get('profit-loss',[HomeController::class,'loss_profit'])->name('profit_loss');
    Route::get('account-client-statement',[HomeController::class,'statement'])->name('account_statement');
    Route::get('football-details',[HomeController::class,'football_details'])->name('Football-Details');
    Route::get('tennis-details',[HomeController::class,'tennis_details'])->name('tenis-details');
    Route::get('basketball-details',[HomeController::class,'basket_details'])->name('Basketball-details');
    Route::get('kabaddi-details',[HomeController::class,'kabaddi_details'])->name('Kabaddi-details');
    Route::get('race_20_20',[HomeController::class,'race20'])->name('race20');
    Route::get('queen_20',[HomeController::class,'queen_20'])->name('queen');
    Route::get('andar_bahar2',[HomeController::class,'andarbahar2'])->name('andarbahar2');
    Route::get('dragon_tiger',[HomeController::class,'dragon_tiger'])->name('dragon-tiger');
    Route::get('transferstatement',[HomeController::class,'transferstatement'])->name('transferstatement');
    Route::get('changepassword',[HomeController::class,'changepassword'])->name('changepassword');
    
});
Route::group(['prefix' => 'admin'], function () {

    Route::get('/', [AdminController::class, 'admin_login'])->name('admin_login');

    Route::post('admin-submit', [AdminController::class, 'admin_submit'])->name('admin_submit');

 
    Route::middleware('agent.login.check')->group(function () {
    Route::get('agent-home', [AdminController::class, 'agent'])->name('agent-home');    
    Route::get('admin-logout',[AdminController::class,'logout'])->name('admin-logout');

    Route::get('dashboard',[AgentManagementController::class,'dashboard'])->name('dashboard');

    Route::get('user-statement',[AgentManagementController::class,'user_account_stat'])->name('user-statement-account-statement');

    Route::get('sup-listing',[AdminController::class,'super_master'])->name('super-master-listing');
    Route::get('super-master-listing-search',[AdminController::class,'super_master_search'])->name('super-master-listing-search');
    Route::get('position',[PositionController::class,'position_taking'])->name('position-taking-listing');

    Route::post('position-update',[PositionController::class,'positionupdate'])->name('position-update');


    Route::get('agency-management',[TransferController::class,'management_transfer'])->name('agency-management-transfer');

    Route::post('transfer-amount',[TransferController::class,'transferAmount'])->name('transfer-amount');
    Route::get('all-member-list',[TransferController::class,'allmemberlist'])->name('all-member-list');

    Route::post('bulk-tranfer-amount',[TransferController::class,'bulktransferAmount'])->name('bulk-tranfer-amount');

    Route::get('exposure',[ExposureController::class,'net_exposure'])->name('net-exposure');
    
    Route::get('bet-ticker',[BetTicketController::class,'bet_ticker'])->name('bet-ticker');
        
    Route::post('bet-ticker',[BetTicketController::class,'bet_ticker'])->name('bet-ticker');

    Route::get('p-l-market',[ReportController::class,'p_l_market'])->name('p-and-l-report-by-market');
    Route::post('p-l-market',[ReportController::class,'p_l_market'])->name('p-and-l-report-by-market');

    Route::get('p-l-market-report-download',[ReportController::class,'plmarketreportdownload'])->name('p-l-market-report-download');


    Route::get('p-l-agent-report-download',[ReportController::class,'plagentreportdownload'])->name('p-l-agent-report-download');


    Route::get('p-l-agent',[ReportController::class,'p_l_agent'])->name('p-and-l-report-by-agent');
    Route::post('p-l-agent',[ReportController::class,'p_l_agent'])->name('p-and-l-report-by-agent');

    Route::get('casino',[AdminController::class,'casino'])->name('casino-report');
    Route::get('user-transfer',[AdminController::class,'user_transfer'])->name('user-transfer-statement');
    
    Route::get('user-listing',[AdminController::class,'user_listing'])->name('user-listing');
    Route::get('bet-list',[BetController::class,'bet_list'])->name('bet-list');
    Route::get('bet-limit',[BetController::class,'bet_limit'])->name('bet-limit');
    Route::get('bet-limit-update',[BetController::class,'bet_limit_update'])->name('bet-limit-update');


    Route::get('bet-list-download',[BetController::class,'bet_list_download'])->name('bet-list-download');

    Route::get('bet-delete',[BetController::class,'betdelete'])->name('delete-bet');
    Route::post('bet-list',[BetController::class,'bet_list'])->name('bet-list');

    Route::get('su-list-live',[BetController::class,'bet_list_live'])->name('bet-list-live');

    Route::get('banner-update',[AdminController::class,'banner_update'])->name('banner-update');
    Route::get('news-update',[ExtraController::class,'news_update'])->name('news-update');
    Route::get('client-notification',[ExtraController::class,'client_notification'])->name('client-notification');
    Route::post('update-notification',[ExtraController::class,'update_notification'])->name('update-notification');

    Route::get('balance-log',[PlayerLogReportController::class,'balance_log'])->name('balance-log');
    Route::post('balance-log',[PlayerLogReportController::class,'balance_log'])->name('balance-log');

    Route::get('player-betting-history',[PlayerLogReportController::class,'my_bets_report'])->name('player-betting-history');
    Route::get('my-bets-report',[PlayerLogReportController::class,'my_bets_report'])->name('my-bets-report');
    Route::post('player-betting-history',[PlayerLogReportController::class,'my_bets_report'])->name('player-betting-history');
    Route::post('my-bets-report',[PlayerLogReportController::class,'my_bets_report'])->name('my-bets-report');

    Route::get('profit-loss',[PlayerLogReportController::class,'profit_loss_report'])->name('player-profit-and-loss');
    Route::get('profit-loss-report',[PlayerLogReportController::class,'profit_loss_report'])->name('profit-loss-report');

    Route::post('profit-loss',[PlayerLogReportController::class,'profit_loss_report'])->name('player-profit-and-loss');
    Route::post('profit-loss-report',[PlayerLogReportController::class,'profit_loss_report'])->name('profit-loss-report');

    Route::get('profit-loss-view-bets',[PlayerLogReportController::class,'profit_loss_view_bets'])->name('profit-loss-view-bets');
    Route::get('chips-analysis',[PlayerLogReportController::class,'chips_analysis'])->name('chips-analysis');

    Route::get('market-setting',[MarketSettingsController::class,'market_settings'])->name('market-settings');
    Route::get('declare-market',[MarketSettingsController::class,'market_settings'])->name('declare-market');
    Route::get('match-lock',[MarketSettingsController::class,'market_settings'])->name('matchlock');
    Route::get('market-rollback',[MarketSettingsController::class,'market_settings'])->name('market-rollback');
    Route::get('global-setting',[MarketSettingsController::class,'global_setting'])->name('global-settings');

    Route::post('global-currency-update', [MarketSettingsController::class,'globalcurrencyupdate'])->name('global-currency-update');


    Route::get('online-user',[AdminController::class,'online_user'])->name('online-user');
    Route::get('delete-bet-history',[AdminController::class,'delete_bet_history'])->name('delete-bet-history');

    Route::get('fancy-setting',[AdminController::class,'fancy_setting'])->name('fancy-setting');
    Route::get('match-setting',[AdminController::class,'match_setting'])->name('match-setting');
    Route::get('match-limit',[AdminController::class,'match_limit'])->name('match-limit');
    Route::get('suspend-all',[AdminController::class,'suspend_market'])->name('suspend-all-market');
    Route::get('score-card',[AdminController::class,'score_tv'])->name('score-card-and-tv');
 
 
    Route::get('prifitloss-downline',[AdminController::class,'profitloss_downline'])->name('profitLoss-report-by-downline');
    Route::get('prifitloss-market',[AdminController::class,'prifitloss_market'])->name('profitLoss-report-by-market');
    Route::get('risk-management',[AdminController::class,'risk_management'])->name('risk-management');

    Route::get('cash-banking',[CashbankingController::class,'cash_banking'])->name('cash-banking');
    Route::post('cash-banking',[CashbankingController::class,'cash_banking'])->name('cash-banking');
    Route::get('/update-payment-submit', [CashbankingController::class,'updatepaymentsubmit'])->name('update-payment-submit');
    Route::post('cash-banking-submit',[CashbankingController::class,'bankingSubmit'])->name('cash-banking-submit');   
    Route::get('/banking-log/{id?}', [CashbankingController::class,'bankinglog'])->name('banking-log'); 

    Route::get('password-history',[AdminController::class,'password_history'])->name('password-history');
    Route::get('commission',[AdminController::class,'commission'])->name('commission');
    Route::get('market-analysis',[AdminController::class,'market_analysis'])->name('market-analysis');
    Route::get('void-market',[AdminController::class,'void_market'])->name('void-markets');
    Route::get('white-label',[AdminController::class,'white_label'])->name('white-lable');
    Route::get('new-super-user',[AdminController::class,'new_super'])->name('new-super-master');
    
    Route::get('transfer-statement',[AdminController::class,'transfer_statement'])->name('transfer-statement');
    Route::get('casino-result',[AdminController::class,'casino_result'])->name('casino-result');
    Route::get('game-report',[AdminController::class,'game_report'])->name('game-report');
    Route::get('create-account',[AdminController::class,'create_account'])->name('create-account');
    Route::get('live-casino',[AdminController::class,'live_casino'])->name('live-casino');
    Route::get('notification',[AdminController::class,'notifi_cation'])->name('notification');

    Route::get('account-statement',[AgentManagementController::class,'account_statement'])->name('account-statement');

    Route::post('account-statement',[AgentManagementController::class,'account_statement'])->name('account-statement');

    Route::get('account-statement-download',[AgentManagementController::class,'account_statement_download'])->name('account-statement-download');

    Route::get('/admin-website-dashboard', [AdminController::class, 'admin_website_dashboard'])->name('admin-website-dashboard');
    Route::get('admin_website_dashboard_detail/{id}',[AdminController::class,'admin_website_dashboard_detail'])->name('admin_website_dashboard_detail');
    Route::get('admin_website_dashboard_detail_reject/{id}/{website}/{game}',[AdminController::class,'admin_website_dashboard_detail_reject'])->name('admin_website_dashboard_detail_reject');
    Route::get('admin_website_dashboard_detail_accept/{id}/{website}/{game}',[AdminController::class,'admin_website_dashboard_detail_accept'])->name('admin_website_dashboard_detail_accept');

    Route::get('message-report',[AdminController::class,'message_report'])->name('message-report');
    Route::get('client_account_statement',[AdminController::class,'client_account_statement'])->name('client-account-statement');
    // Route::post('password-change/{id}',[AdminController::class,'password_change'])->name('admin-password-change');
    Route::get('smdemo1-agent-listing-demoag5',[AdminController::class,'demo_2'])->name('smdemo1-agent-listing-demoag5');
    Route::get('agent-listing2',[AdminController::class,'demo_1'])->name('agent-listing2');
    Route::any('agent-listing',[AdminController::class,'agent_listing'])->name('agent-listing');
    Route::get('agent-listing-demoag5',[AdminController::class,'demo_4'])->name('agent-listing-demoag5');
    Route::get('create-new-user',[AdminController::class,'new_user'])->name('create-new-user');
    Route::get('race20',[AdminController::class,'race20'])->name('admin-race-20');
    Route::get('new-agent',[AdminController::class,'new_agent'])->name('new-agent');
 
    Route::get('agent-dashboard',[AdminController::class,'agent_dashboard'])->name('agent-dashboard');
    Route::post('new-agent-submit',[LoginSubmitController::class,'new_agent_submit'])->name('new-agent-submit');
    Route::get('admin-detail',[AdminController::class,'admin_detail'])->name('admin-details');
    Route::post('agent-update',[LoginSubmitController::class,'agent_update'])->name('agent-update');
    Route::get('update-password',[AdminController::class,'password_update'])->name('update-password');
    Route::post('new-admin',[AdminController::class,'admin_add_submit'])->name('admin-add-submit');
    Route::post('admin-update',[LoginSubmitController::class,'admin_update'])->name('admin-update');
    Route::post('update-admin-status', [AdminController::class, 'update_Status'])->name('update-status');
    Route::post('admin-user-create', [LoginSubmitController::class, 'admin_user'])->name('admin-user-create');
    Route::get('user-detail',[AdminUserController::class,'user_detail'])->name('user-details');
    Route::post('user-access',[AdminUserController::class,'user_access'])->name('admin-user-access');
    Route::post('super-master-user',[AdminUserController::class,'master_user_access'])->name('super-master-user');
    Route::get('user-access-edit',[AdminUserController::class,'user_access_edit'])->name('adminuser-details');
    Route::post('user-access-update',[AdminUserController::class,'user_access_update'])->name('update-useraccess');
    Route::get('get-user-data', [AdminUserController::class,'get_user_data'])->name('get-user-data');
    Route::get('update-user-data', [AdminUserController::class,'update_user_data'])->name('update-user-data');
    Route::post('white-label-create',[AdminUserController::class,'white_label_add'])->name('white-label-submit');
    Route::post('update-status',[AdminUserController::class,'update_whitelevel_status'])->name('update-whitelevel-status');
    Route::get('update-user-password',[AdminUserController::class,'update_user_password'])->name('update-user-password');
    Route::post('logout-all',[AdminController::class,'logout_all'])->name('logout-all-user');
    
    // Cricket, Football, Tennis etc Games routes
    //Cricket
    Route::get('cricket/game-list',[GameController::class,'adminCricketGameList'])->name('admin-cricket-game-list');
    Route::get('cricket/game-create',[GameController::class,'createCricketGame'])->name('admin-cricket-game-create');
    Route::post('cricket/game-submit',[GameController::class,'submitCricketGame'])->name('admin-cricket-game-submit');
    Route::get('cricket/match-list',[GameController::class,'adminCricketMatchList'])->name('admin-cricket-match-list');
    // Route::get('cricket/game/search',[GameController::class])
    Route::get('cricket/delete/new/match/{id}',[GameController::class,'adminCricketRemoveMatch'])->name('delete.cricket.match.new');
    Route::get('cricket/show/new/match/{id}/{domain}/{game}',[GameController::class,'adminCricketshowmatch'])->name('show.cricket.match');
    Route::post('cricket/game/update/admin',[GameController::class,'adminCricketGameUpdate'])->name('update.cricket.game.admin');
    
    Route::get('cricket/match-create/{id?}',[GameController::class,'createCricketMatch'])->name('admin-cricket-match-create');
    Route::post('cricket/match-submit',[GameController::class,'submitCricketMatch'])->name('admin-cricket-match-submit');
    Route::post('cricket/match-submit-score',[GameController::class,'submitCricketMatchScore'])->name('admin-cricket-match-submit-score');
    Route::get('cricket/match-submit-score-clear/{id?}',[GameController::class,'submitCricketMatchScoreClear'])->name('admin-cricket-match-submit-score-clear');
    Route::get('cricket/activate-back-status/{id?}',[GameController::class,'activateBackStatus'])->name('activate.back_status');
    Route::get('cricket/deactivate-back-status/{id?}',[GameController::class,'deactivateBackStatus'])->name('deactivate.back_status');
    Route::get('cricket/activate-lay-status/{id?}',[GameController::class,'activateLayStatus'])->name('activate.lay_status');
    Route::get('cricket/deactivate-lay-status/{id?}',[GameController::class,'deactivateLayStatus'])->name('deactivate.lay_status');
    Route::get('cricket/change-match-status/{id?}/{gameid?}',[GameController::class,'changeMatchStatus'])->name('change-match-status');
    Route::post('cricket/admin-change-cricket-match-status-submit',[GameController::class,'changeMatchStatusSubmit'])->name('admin-change-cricket-match-status-submit');
    Route::get('cricket/activate-game-status/{id?}',[GameController::class,'activateCricketGameStatus'])->name('activate.cricket.game.status');
    Route::get('cricket/deactivate-game-status/{id?}',[GameController::class,'deactivateCricketGameStatus'])->name('deactivate.cricket.game.status');
    Route::post('cricket/match-update',[GameController::class,'updateCricketMatch'])->name('cricket.match.update');
    Route::get('cricket/delete-cricket-match/{id?}',[GameController::class,'deleteCricketMatch'])->name('delete.cricket.match');
    
    
    //Football
    Route::get('football/game-create',[FootballGameController::class,'createFootballGame'])->name('admin-football-game-create');
    Route::post('football/game-submit',[FootballGameController::class,'submitFootballGame'])->name('admin-football-game-submit');
    Route::get('football/game-list',[FootballGameController::class,'adminFootballGameList'])->name('admin-football-game-list');
    
    Route::get('football/game/delete/admin/{id}',[FootballGameController::class,'adminFootballRemoveGame'])->name('delete.football.game.new');
    Route::post('football/game/update/admin',[FootballGameController::class,'adminFootballGameUpdate'])->name('update.football.game.admin');
    
    Route::get('football/match-create/{id?}',[FootballGameController::class,'createFootballMatch'])->name('admin-football-match-create');
    Route::post('football/match-submit',[FootballGameController::class,'submitFootballMatch'])->name('admin-football-match-submit');
    Route::post('football/match-submit-score',[FootballGameController::class,'submitFootballMatchScore'])->name('admin-football-match-submit-score');
    Route::post('football/match-update',[FootballGameController::class,'updateFootballMatch'])->name('football.match.update');
    Route::get('football/match-list',[FootballGameController::class,'adminFootballMatchList'])->name('admin-football-match-list');
    Route::get('football/activate-back-status/{id?}',[FootballGameController::class,'activateBackStatus'])->name('football.activate.back_status');
    Route::get('football/deactivate-back-status/{id?}',[FootballGameController::class,'deactivateBackStatus'])->name('football.deactivate.back_status');
    Route::get('football/activate-lay-status/{id?}',[FootballGameController::class,'activateLayStatus'])->name('football.activate.lay_status');
    Route::get('football/deactivate-lay-status/{id?}',[FootballGameController::class,'deactivateLayStatus'])->name('football.deactivate.lay_status');
    Route::get('football/change-match-status/{id?}/{gameid?}',[FootballGameController::class,'changeMatchStatus'])->name('change-football-match-status');
    Route::post('football/admin-change-football-match-status-submit',[FootballGameController::class,'changeMatchStatusSubmit'])->name('admin-change-football-match-status-submit');
    Route::get('football/activate-game-status/{id?}',[FootballGameController::class,'activateFootballGameStatus'])->name('activate.football.game.status');
    Route::get('football/deactivate-game-status/{id?}',[FootballGameController::class,'deactivateFootballGameStatus'])->name('deactivate.football.game.status');
    Route::get('football/delete-football-match/{id?}',[FootballGameController::class,'deleteFootballMatch'])->name('delete.football.match');
    
    //Tennis
    Route::get('tennis/game-create',[TennisGameController::class,'createTennisGame'])->name('admin-tennis-game-create');
    Route::post('tennis/game-submit',[TennisGameController::class,'submitTennisGame'])->name('admin-tennis-game-submit');
    Route::get('tennis/game-list',[TennisGameController::class,'adminTennisGameList'])->name('admin-tennis-game-list');
    
    Route::get('tennis/game/delete/admin/{id}',[TennisGameController::class,'adminTennisRemoveGame'])->name('delete.tennis.game.new');
    Route::post('tennis/game/update/admin',[TennisGameController::class,'adminTennisGameUpdate'])->name('update.tennis.game.admin');
    
    Route::get('tennis/match-create/{id?}',[TennisGameController::class,'createTennisMatch'])->name('admin-tennis-match-create');
    Route::post('tennis/match-submit',[TennisGameController::class,'submitTennisMatch'])->name('admin-tennis-match-submit');
    Route::post('tennis/match-submit-score',[TennisGameController::class,'submitTennisMatchScore'])->name('admin-tennis-match-submit-score');
    Route::post('tennis/match-update',[TennisGameController::class,'updateTennisMatch'])->name('tennis.match.update');
    Route::get('tennis/match-list',[TennisGameController::class,'adminTennisMatchList'])->name('admin-tennis-match-list');
    Route::get('tennis/activate-back-status/{id?}',[TennisGameController::class,'activateBackStatus'])->name('tennis.activate.back_status');
    Route::get('tennis/deactivate-back-status/{id?}',[TennisGameController::class,'deactivateBackStatus'])->name('tennis.deactivate.back_status');
    Route::get('tennis/activate-lay-status/{id?}',[TennisGameController::class,'activateLayStatus'])->name('tennis.activate.lay_status');
    Route::get('tennis/deactivate-lay-status/{id?}',[TennisGameController::class,'deactivateLayStatus'])->name('tennis.deactivate.lay_status');
    Route::get('tennis/change-match-status/{id?}/{gameid?}',[TennisGameController::class,'changeMatchStatus'])->name('change-tennis-match-status');
    Route::post('tennis/admin-change-tennis-match-status-submit',[TennisGameController::class,'changeMatchStatusSubmit'])->name('admin-change-tennis-match-status-submit');
    Route::get('tennis/activate-game-status/{id?}',[TennisGameController::class,'activateTennisGameStatus'])->name('activate.tennis.game.status');
    Route::get('tennis/deactivate-game-status/{id?}',[TennisGameController::class,'deactivateTennisGameStatus'])->name('deactivate.tennis.game.status');
    Route::get('tennis/delete-tennis-match/{id?}',[TennisGameController::class,'deleteTennisMatch'])->name('delete.tennis.match');

    
    //HorseRacing
    Route::get('horseracing/game-create',[HorseRacingGameController::class,'createHorseRacingGame'])->name('admin-horseracing-game-create');
    Route::post('horseracing/game-submit',[HorseRacingGameController::class,'submitHorseRacingGame'])->name('admin-horseracing-game-submit');
    
    Route::get('horseracing/time-slot-create',[HorseRacingGameController::class,'createHorseRacingGameTimeSlot'])->name('admin-horseracing-time-slot-create');
    Route::post('horseracing/time-slot-submit',[HorseRacingGameController::class,'submitHorseRacingGameTimeSlot'])->name('admin-horseracing-time-slot-submit');
    Route::get('horseracing/time-slot-list',[HorseRacingGameController::class,'adminHorseRacingGameTimeSlotList'])->name('admin-horseracing-time-slot-list');
    Route::get('horseracing/activate-timeslot-status/{id?}',[HorseRacingGameController::class,'activateHorseRacingTimeslotStatus'])->name('activate.horseracing.timeslot.status');
    Route::get('horseracing/deactivate-timeslot-status/{id?}',[HorseRacingGameController::class,'deactivateHorseRacingTimeslotStatus'])->name('deactivate.horseracing.timeslot.status');
    Route::get('horseracing/game-list',[HorseRacingGameController::class,'adminHorseRacingGameList'])->name('admin-horseracing-game-list');
    
    Route::get('horseracing/game/delete/admin/{id}',[HorseRacingGameController::class,'adminHorseRacingRemoveGame'])->name('delete.HorseRacing.game.new');
    Route::post('horseracing/game/update/admin',[HorseRacingGameController::class,'adminHorseRacingGameUpdate'])->name('update.HorseRacing.game.admin');
    
    Route::get('horseracing/match-create/{id?}',[HorseRacingGameController::class,'createHorseRacingMatch'])->name('admin-horseracing-match-create');
    Route::post('horseracing/match-submit',[HorseRacingGameController::class,'submitHorseRacingMatch'])->name('admin-horseracing-match-submit');
    Route::post('horseracing/match-update',[HorseRacingGameController::class,'updateHorseRacingMatch'])->name('horseracing.match.update');
    Route::get('horseracing/match-list',[HorseRacingGameController::class,'adminHorseRacingMatchList'])->name('admin-horseracing-match-list');
    Route::get('horseracing/activate-back-status/{id?}',[HorseRacingGameController::class,'activateBackStatus'])->name('horseracing.activate.back_status');
    Route::get('horseracing/deactivate-back-status/{id?}',[HorseRacingGameController::class,'deactivateBackStatus'])->name('horseracing.deactivate.back_status');
    Route::get('horseracing/activate-lay-status/{id?}',[HorseRacingGameController::class,'activateLayStatus'])->name('horseracing.activate.lay_status');
    Route::get('horseracing/deactivate-lay-status/{id?}',[HorseRacingGameController::class,'deactivateLayStatus'])->name('horseracing.deactivate.lay_status');
    Route::get('horseracing/change-match-status/{id?}/{time_slot_id?}',[HorseRacingGameController::class,'changeMatchStatus'])->name('change-horseracing-match-status');
    Route::post('horseracing/admin-change-horseracing-match-status-submit',[HorseRacingGameController::class,'changeMatchStatusSubmit'])->name('admin-change-horseracing-match-status-submit');
    Route::get('horseracing/activate-game-status/{id?}',[HorseRacingGameController::class,'activateHorseRacingGameStatus'])->name('activate.horseracing.game.status');
    Route::get('horseracing/deactivate-game-status/{id?}',[HorseRacingGameController::class,'deactivateHorseRacingGameStatus'])->name('deactivate.horseracing.game.status');
    Route::get('horseracing/delete-horseracing-match/{id?}',[HorseRacingGameController::class,'deleteHorseRacingMatch'])->name('delete.horseracing.match');
    
    //Greyhound Racing
    Route::get('greyhoundracing/game-create',[GreyhoundRacingGameController::class,'createGreyhoundRacingGame'])->name('admin-greyhoundracing-game-create');
    Route::post('greyhoundracing/game-submit',[GreyhoundRacingGameController::class,'submitGreyhoundRacingGame'])->name('admin-greyhoundracing-game-submit');
    Route::get('greyhoundracing/game-list',[GreyhoundRacingGameController::class,'adminGreyhoundRacingGameList'])->name('admin-greyhoundracing-game-list');
    
    Route::get('greyhoundracing/game/delete/admin/{id}',[GreyhoundRacingGameController::class,'adminGreyhoundRacingRemoveGame'])->name('delete.greyhoundracing.game.new');
    Route::post('greyhoundracing/game/update/admin',[GreyhoundRacingGameController::class,'adminGreyhoundRacingGameUpdate'])->name('update.greyhoundracing.game.admin');
    
    Route::get('greyhoundracing/match-create/{id?}',[GreyhoundRacingGameController::class,'createGreyhoundRacingMatch'])->name('admin-greyhoundracing-match-create');
    Route::post('greyhoundracing/match-submit',[GreyhoundRacingGameController::class,'submitGreyhoundRacingMatch'])->name('admin-greyhoundracing-match-submit');
    Route::post('greyhoundracing/match-update',[GreyhoundRacingGameController::class,'updateGreyhoundRacingMatch'])->name('greyhoundracing.match.update');
    Route::get('greyhoundracing/match-list',[GreyhoundRacingGameController::class,'adminGreyhoundRacingMatchList'])->name('admin-greyhoundracing-match-list');
    Route::get('greyhoundracing/activate-back-status/{id?}',[GreyhoundRacingGameController::class,'activateBackStatus'])->name('greyhoundracing.activate.back_status');
    Route::get('greyhoundracing/deactivate-back-status/{id?}',[GreyhoundRacingGameController::class,'deactivateBackStatus'])->name('greyhoundracing.deactivate.back_status');
    Route::get('greyhoundracing/activate-lay-status/{id?}',[GreyhoundRacingGameController::class,'activateLayStatus'])->name('greyhoundracing.activate.lay_status');
    Route::get('greyhoundracing/deactivate-lay-status/{id?}',[GreyhoundRacingGameController::class,'deactivateLayStatus'])->name('greyhoundracing.deactivate.lay_status');
    Route::get('greyhoundracing/change-match-status/{id?}/{time_slot_id?}',[GreyhoundRacingGameController::class,'changeMatchStatus'])->name('change-greyhoundracing-match-status');
    Route::post('greyhoundracing/admin-change-greyhoundracing-match-status-submit',[GreyhoundRacingGameController::class,'changeMatchStatusSubmit'])->name('admin-change-greyhoundracing-match-status-submit');
    Route::get('greyhoundracing/activate-game-status/{id?}',[GreyhoundRacingGameController::class,'activateGreyhoundRacingGameStatus'])->name('activate.greyhoundracing.game.status');
    Route::get('greyhoundracing/deactivate-game-status/{id?}',[GreyhoundRacingGameController::class,'deactivateGreyhoundRacingGameStatus'])->name('deactivate.greyhoundracing.game.status');
    
    Route::get('greyhoundracing/time-slot-create',[GreyhoundRacingGameController::class,'createGreyhoundRacingGameTimeSlot'])->name('admin-greyhoundracing-time-slot-create');
    Route::post('greyhoundracing/time-slot-submit',[GreyhoundRacingGameController::class,'submitGreyhoundRacingGameTimeSlot'])->name('admin-greyhoundracing-time-slot-submit');
    Route::get('greyhoundracing/time-slot-list',[GreyhoundRacingGameController::class,'adminGreyhoundRacingGameTimeSlotList'])->name('admin-greyhoundracing-time-slot-list');
    Route::get('greyhoundracing/activate-timeslot-status/{id?}',[GreyhoundRacingGameController::class,'activateGreyhoundRacingTimeslotStatus'])->name('activate.greyhoundracing.timeslot.status');
    Route::get('greyhoundracing/deactivate-timeslot-status/{id?}',[GreyhoundRacingGameController::class,'deactivateGreyhoundRacingTimeslotStatus'])->name('deactivate.greyhoundracing.timeslot.status');
    Route::get('greyhoundracing/delete-greyhoundracing-match/{id?}',[GreyhoundRacingGameController::class,'deleteGreyhoundRacingMatch'])->name('delete.greyhoundracing.match');



    
});

});