<?php

namespace App\Http\Controllers\admin;

use App\Helpers\QueryHelper;
use App\Models\User;
use App\Models\Admin;
use App\Models\CreditLog;
use App\Models\BetHistory;
use App\Models\Passwordlogs;
use App\Models\PositionTaking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Rules\CustomRule;
use Jenssegers\Agent\Agent;
use App\Models\CricketGame;
use App\Models\CricketMatch;
use App\Models\CricketPlaceBet;
use App\Models\FootballGame;
use App\Models\CricketMatchScore;
use App\Models\CricketMatchScoreDetail;
use App\Models\TennisGame;
use App\Models\ScoreCard;
use Carbon\Carbon;

class GameController extends Controller
{
    // Cricket Game functions goes here


    public function adminCricketRemoveMatch($id)
    {

        $check = CricketGame::where('id', $id)->delete($id);
        if ($check) {
            if ($check) {
                return redirect()->back()->with('success', "Deleted Successfully!");
            } else {
                return redirect()->back()->with('fail', 'Something Went Wrong!');
            }
        } else {
            return redirect()->back()->with('fail', 'Record Not Found!');
        }
    }
    public function adminCricketshowmatch($id, $domain, $game)
    {

        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, "https://" . $domain . "/api/all_games_bet/" . $game . "/" . $id);
        // Execute
        $result = curl_exec($ch);
        // Will dump a beauty json <3
        $response = json_decode($result, true);

        curl_close($ch);
        return view('agent.cricket.admin_cricket_game_show', compact('response', 'game'));
    }
    public function adminCricketGameUpdate(Request $request)
    {
        $check = CricketGame::find($request->match_id);
        if ($check) {
            $done = $check->update(['game_title' => $request->game_title, 'channel_id' => $request->channel_id, 'run_date_time' => $request->run_date_time, 'datetimeGMT' => $request->run_date_time]);
            if ($done) {
                return back()->with('success', "Game Updated Successfully!");
            } else {
                return back()->with('fail', 'Something Went Wrong!');
            }
        } else {
            return back()->with('fail', 'Record Not Found!');
        }
    }

    public function adminCricketGameList(Request $request)
    {
        $query = CricketGame::orderBy('id', 'asc');

        if ($request->has('search') && $request->search != '') {
            $query->where('game_title', 'like', '%' . $request->search . '%');
        }

        $crickets = $query->paginate(10);
        return view('agent.cricket.admin_cricket_game_list', compact('crickets'));
    }

    public function createCricketGame()
    {

        return view('agent.cricket.create-cricket-game');
    }

    public function submitCricketGame(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'game_title' => 'required',
            'run_date_time' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $carbonDate = Carbon::parse($request->run_date_time);

        // Format it to match SQL timestamp format (YYYY-MM-DD HH:MM:SS)
        $sqlTimestamp = $carbonDate->format('Y-m-d H:i:s');
        // $datetime = Carbon::createFromFormat('Y/m/d', $request->run_date_time)->timestamp;
        // dd($sqlTimestamp);
        // $cricket = new CricketGame();
        // $cricket->game_title = $request->game_title;
        // $cricket->run_date_time = $request->run_date_time;
        // $cricket->channel_id = $request->channel_id;
        // $cricket->status = 1;

        // $cricket->save();
        $data = [
            'game_title' => $request->game_title,
            'run_date_time' => $request->run_date_time,
            'datetimeGMT' => $request->run_date_time,
            'channel_id' => $request->channel_id,
            'status' => 1,
        ];
        CricketGame::insert($data);
        return redirect()->route('admin-cricket-game-list')->with('message', 'Game created successfully');
    }

    // public function adminCricketMatchList() {
    //     $crickets = CricketMatch::orderBy('id','desc')->paginate(10);
    //     return view('agent.cricket.admin_cricket_match_list',compact('crickets'));
    // }

    public function createCricketMatch($id)
    {
        $game_id = $id;
        $crickets = CricketMatch::where('game_id', $game_id)->orderBy('id', 'desc')->get();
        $cricket_score = CricketMatchScore::where('game_id', $game_id)->orderBy('id', 'asc')->get();
        $cricket_score_detail = CricketMatchScoreDetail::where('game_id', $game_id)->orderBy('id', 'asc')->first();
        return view('agent.cricket.create-cricket-match', compact('game_id', 'crickets', 'cricket_score', 'cricket_score_detail'));
    }

    public function submitCricketMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cricket = new CricketMatch();
        $cricket->team_name = $request->team_name;
        $cricket->back_value = $request->back_value;
        $cricket->lay_value = $request->lay_value;
        $cricket->match_type = $request->match_type;
        $cricket->status = 1;
        $cricket->back_status = 1;
        $cricket->lay_status = 1;
        $cricket->game_id = $request->game_id;
        $cricket->stake = $request->stake;
        $cricket->save();

        $game_id = $request->game_id;
        $crickets = CricketMatch::where('game_id', $game_id)->orderBy('id', 'desc')->get();
        return redirect()->back();
        // return view('agent.cricket.create-cricket-match',compact('game_id','crickets'))->with('message', 'Match created successfully');
    }
    public function submitCricketMatchScore(Request $request)
    {

        // $validator = Validator::make($request->all(),[
        //     'team_name' => 'required',
        //     'score' => 'required',
        // ]);

        // if($validator->fails()){
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }
        $matchScore = CricketMatchScoreDetail::where('game_id', $request->game_id)->first();

        if ($matchScore) {
            // Update the existing record
            $matchScore->update([
                'game_id' => $request->game_id,
                'team_name_a' => $request->team_name_a,
                'target' => $request->target,
                'play_wicket' => $request->play_wicket,
            ]);
        } else {
            // Insert a new record
            $cricket = new CricketMatchScoreDetail();
            $cricket->game_id = $request->game_id;
            $cricket->team_name_a = $request->team_name_a;
            $cricket->target = $request->target;
            $cricket->play_wicket = $request->play_wicket ?? 0;
            $cricket->save();
        }

        if (!empty($request->score)) {
            $matchScore = CricketMatchScoreDetail::where('game_id', $request->game_id)->first();
            if ($matchScore) {
                if (is_int($request->score)) {
                    // Update the existing record
                    $score_in = $matchScore->play_score + $request->score;
                    $matchScore->update([
                        'play_score' => $score_in,
                    ]);
                }
            } else {
                if (is_int($request->score)) {
                    // Insert a new record
                    $cricket = new CricketMatchScoreDetail();
                    $cricket->game_id = $request->game_id;
                    $cricket->play_score = $request->score;
                    $cricket->save();
                }
            }

            $cricket = new CricketMatchScore();
            $cricket->game_id = $request->game_id;
            $cricket->team_name = $request->team_name;
            $cricket->score = $request->score;
            $cricket->save();
        }
        return redirect()->back();
        // return view('agent.cricket.create-cricket-match',compact('game_id','crickets'))->with('message', 'Match created successfully');
    }
    public function submitCricketMatchScoreClear($game_id)
    {

        $cricket = CricketMatchScoreDetail::where('game_id', $game_id)->first();
        $over = $cricket->play_over + 1;
        CricketMatchScoreDetail::where('game_id', $game_id)->update(['play_over' => $over]);
        CricketMatchScore::where('game_id', $game_id)->delete();


        return redirect()->back();
        // return view('agent.cricket.create-cricket-match',compact('game_id','crickets'))->with('message', 'Match created successfully');
    }

    public function updateCricketMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cricket = CricketMatch::findOrFail($request->match_id);
        $cricket->team_name = $request->team_name;
        $cricket->back_value = $request->back_value;
        $cricket->lay_value = $request->lay_value;
        $cricket->match_type = $request->match_type;
        $cricket->save();

        $game_id = $request->game_id;
        $crickets = CricketMatch::where('game_id', $game_id)->orderBy('id', 'desc')->get();

        return view('agent.cricket.create-cricket-match', compact('game_id', 'crickets'))->with('message', 'Match updated successfully');
    }

    public function activateBackStatus($id)
    {
        $cricket = CricketMatch::findOrFail($id);
        $cricket->back_status = 1;
        $cricket->save();
        return redirect()->back();
    }
    public function deactivateBackStatus($id)
    {
        $cricket = CricketMatch::findOrFail($id);
        $cricket->back_status = 0;
        $cricket->save();
        return redirect()->back();
    }
    public function activateLayStatus($id)
    {
        $cricket = CricketMatch::findOrFail($id);
        $cricket->lay_status = 1;
        $cricket->save();
        return redirect()->back();
    }
    public function deactivateLayStatus($id)
    {
        $cricket = CricketMatch::findOrFail($id);
        $cricket->lay_status = 0;
        $cricket->save();
        return redirect()->back();
    }

    public function activateCricketGameStatus($id)
    {
        $cricket = CricketGame::findOrFail($id);
        $cricket->status = 1;
        $cricket->save();
        return redirect()->back();
    }
    public function deactivateCricketGameStatus($id)
    {
        $cricket = CricketGame::findOrFail($id);
        $cricket->status = 0;
        $cricket->save();
        return redirect()->back();
    }

    public function changeMatchStatus($id, $gameid)
    {
        $cricket = CricketMatch::findOrFail($id);
        $currentStatus = $cricket->status;
        $win_loss = $cricket->win_loss;

        return view('agent.cricket.change-cricket-match-status', compact('id', 'gameid', 'currentStatus', 'win_loss', 'cricket'));
    }

    public function changeMatchStatusSubmit(Request $request)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://crickekbuz.art/api/profit_loss/' . $request->id . '/cricket/' . $request->win_loss,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://newsilver.art/api/profit_loss/' . $request->id . '/cricket/' . $request->win_loss,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gold365.art/api/profit_loss/' . $request->id . '/cricket/' . $request->win_loss,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://allpanel.art/api/profit_loss/' . $request->id . '/cricket/' . $request->win_loss,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://laserclub.art/api/profit_loss/' . $request->id . '/cricket/' . $request->win_loss,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        //   $response == "done"
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cricket = CricketMatch::findOrFail($request->id);
        $cricket->status = $request->status;
        // if(isset($request->win_loss)){
        $cricket->win_loss = $request->win_loss;

        // $winnerSelection = CricketPlaceBet::where('match_id',$cricket->id)->where('bet_result',null)->where('back_lay',$request->win_loss)
        //                       ->join('admins', 'cricket_place_bet.user_id', '=', 'admins.id')
        //                       ->get();
        // // dd($winnerSelection);
        // if(!empty($winnerSelection)){
        //   foreach($winnerSelection as $winner){
        //       $total_balance = $winner->balance + $winner->bet_profit;

        //       $admin = Admin::findOrFail($winner->user_id);
        //       $admin->balance = $total_balance;
        //       $admin->save();
        //   } 
        // }

        //  $placeBetResults = CricketPlaceBet::where('match_id',$cricket->id)->where('bet_result',null)->where('back_lay',$request->win_loss)->get();

        // if(!empty($placeBetResults)){
        //   foreach($placeBetResults as $placeBetResult){
        //       $placeBetResult->bet_result = 1; //1=win, 2=Lose
        //       $placeBetResult->save();
        //   }
        // }

        // if($request->win_loss=='back'){
        //     $winLoss = 'lay';
        // }
        // if($request->win_loss=='lay'){
        //     $winLoss = 'back';
        // }

        // $LoseResults = CricketPlaceBet::where('match_id',$cricket->id)->where('bet_result',null)->where('back_lay',$winLoss)->get();

        // if(!empty($LoseResults)){
        //   foreach($LoseResults as $LoseResult){
        //       $LoseResult->bet_result = 2; // 2=Lose
        //       $LoseResult->save();
        //   }
        // }

        // }
        $cricket->save();

        return redirect()->route('admin-cricket-game-list')->with('message', 'Match status changed successfully');
    }

    public function deleteCricketMatch($id)
    {
        $cricket = CricketMatch::findOrFail($id);
        $cricket->delete();
        return redirect()->back()->with('message', 'Match deleted successfully');
    }

    public function getGameListApi()
    {
        $today = Carbon::today();
        $tomorrow1 = Carbon::tomorrow();
        $fourDaysLater = $tomorrow1->addDays(5); // Date 4 days from tomorrow
        $fourDaysprevious = $today->subDays(5); // Date 4 days from yesturday

        // Format the date into SQL timestamp format
        $sqlTimestamp = $fourDaysLater->format('Y-m-d H:i:s');


        $crickets = CricketGame::whereDate('run_date_time', '>=', $fourDaysprevious)
            ->WhereDate('run_date_time', '<=', $fourDaysLater)
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')  // Order by datetimeGMT in ascending order
            ->get();
        return response()->json($crickets);
    }

    public function getMatchListApi($game_id)
    {
        $match_odds = CricketMatch::where('game_id', $game_id)->where('match_type', 'match_odds')->where('status', 1)->orderBy('id', 'desc')->get();
        $bookmaker = CricketMatch::where('game_id', $game_id)->where('match_type', 'bookmaker')->where('status', 1)->orderBy('id', 'desc')->get();
        $to_win_the_toss = CricketMatch::where('game_id', $game_id)->where('match_type', 'to_win_the_toss')->where('status', 1)->orderBy('id', 'desc')->get();
        $fancy = CricketMatch::where('game_id', $game_id)->where('match_type', 'fancy')->where('status', 1)->orderBy('id', 'desc')->get();
        $run_bhav = CricketMatch::where('game_id', $game_id)->where('match_type', 'run_bhav')->orderBy('id', 'desc')->get();
        $over_by_over_session_market = CricketMatch::where('game_id', $game_id)->where('match_type', 'over_by_over_session_market')->where('status', 1)->orderBy('id', 'desc')->get();
        $ball_by_ball_session_market = CricketMatch::where('game_id', $game_id)->where('match_type', 'ball_by_ball_session_market')->where('status', 1)->orderBy('id', 'desc')->get();
        $tied_match = CricketMatch::where('game_id', $game_id)->where('match_type', 'tied_match')->where('status', 1)->orderBy('id', 'desc')->get();
        $scoreCard = ScoreCard::where('match_id', $game_id)->first();
        // dd($crickets);
        return response()->json([
            'match_odds' => $match_odds,
            'bookmaker' => $bookmaker,
            'to_win_the_toss' => $to_win_the_toss,
            'fancy' => $fancy,
            'run_bhav' => $run_bhav,
            'over_by_over_session_market' => $over_by_over_session_market,
            'ball_by_ball_session_market' => $ball_by_ball_session_market,
            'tied_match' => $tied_match,
            'scorecard' => $scoreCard
        ]);
    }

    public function getSingleGameByIdApi($game_id)
    {
        $game = CricketGame::findOrFail($game_id);
        // dd($crickets);
        return response()->json($game);
    }

    public function placeBetForCricketApi(Request $request)
    {
        // return $request->all();
        $placeBet = new CricketPlaceBet();
        $placeBet->match_id = $request['match_id'];
        $placeBet->bet_odds = $request['bet_odds'];
        $placeBet->bet_stake = $request['bet_stake'];
        $placeBet->back_lay = $request['back_lay'];
        $placeBet->bet_profit = $request['bet_profit'];
        $placeBet->user_id = $request['user_id'];
        $placeBet->team_name = $request['bet_team_name'];
        $placeBet->save();

        $played_matches = CricketPlaceBet::where('user_id', $request['user_id'])->where('bet_result', null)->get();

        return response()->json([
            "place_bet_id" => $placeBet->id,
            "bet_request" => $request->all(),
            "played_matches" => $played_matches
        ]);
    }

    public function getCricketMatchResultApi()
    {
        $result = CricketMatch::whereNotNull('win_loss')->orderBy('run_date_time', 'asc')->first();

        return response()->json([
            'match_result' => $result
        ]);
    }

    public function getAllGamesListApi()
    {
        $crickets = CricketGame::where('status', 1)->orderBy('run_date_time', 'asc')->limit(3)->get();

        //           $today = Carbon::today();
        //         $tomorrow1 = Carbon::tomorrow();
        //         $now = Carbon::now();

        // // Add 4 days to the current date
        // $tomorrow = $now->addDays(4);

        //       $crickets = CricketGame::whereDate('datetimeGMT','>=', $today)
        //             ->orWhereDate('datetimeGMT','<=', $fourDaysLater)
        //             ->with('match_list')
        //             ->orderBy('datetimeGMT', 'asc') 
        //             ->limit(3)// Order by datetimeGMT in ascending order
        //             ->get();
        $footballs = FootballGame::where('status', 1)->orderBy('run_date_time', 'asc')->limit(3)->get();
        $tennises = TennisGame::where('status', 1)->orderBy('run_date_time', 'asc')->limit(3)->get();


        // return $crickets;
        return response()->json([
            'crickets' => $crickets,
            'footballs' => $footballs,
            'tennises' => $tennises
        ]);
    }
}
