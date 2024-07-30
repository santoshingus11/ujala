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
use App\Models\FootballGame;
use App\Models\FootballMatchScore;
use App\Models\FootballMatch;
use Carbon\Carbon;


class FootballGameController extends Controller
{
    // Football Game functions goes here
    
    public function adminFootballGameList(Request $request) {
         $query = FootballGame::orderBy('id', 'desc');

    if ($request->has('search') && $request->search != '') {
        $query->where('game_title', 'like', '%' . $request->search . '%');
    }

    $footballs = $query->paginate(10);
        // $today = date('Y-m-d');
        // $footballs = FootballGame::orderBy('id','desc')->paginate(10);
        return view('agent.football.admin_football_game_list',compact('footballs'));
    }
    
    
    
    public function adminFootballRemoveGame($id){
        $check = FootballGame::find($id);
        if($check){
            if($check->delete()){
                return back()->with('success',"Deleted Successfully!");
            }else{
                return back()->with('fail','Something Went Wrong!');
            }
            
        }else{
            return back()->with('fail','Record Not Found!');
        }
    }
    public function adminFootballGameUpdate(Request $request){
        $check = FootballGame::find($request->match_id);
        if($check){
            $done = $check->update(['game_title'=>$request->game_title,'channel_id'=>$request->channel_id,'run_date_time'=>$request->run_date_time]);
            if($done){
                return back()->with('success',"Game Updated Successfully!");
            }else{
                return back()->with('fail','Something Went Wrong!');
            }
        }else{
            return back()->with('fail','Record Not Found!');
        }
       
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function createFootballGame(){
        
        return view('agent.football.create-football-game');
    }
    
    public function submitFootballGame (Request $request) {
        $validator = Validator::make($request->all(),[
            'game_title' => 'required',
            'run_date_time'=>'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $football = new FootballGame();
        $football->game_title = $request->game_title;
        $football->run_date_time = $request->run_date_time;
        $football->status = 1;
        $football->save();

        return redirect()->route('admin-football-game-list')->with('message', 'Game created successfully');
    }
     
    public function createFootballMatch($id){
        $game_id = $id;
        $footballs = FootballMatch::where('game_id',$game_id)->orderBy('id','desc')->get();
        $footballscore = FootballMatchScore::where('game_id',$game_id)->first();
        return view('agent.football.create-football-match',compact('game_id','footballs','footballscore'));
    }
    
    public function submitFootballMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $football = new FootballMatch();
        $football->team_name = $request->team_name;
        $football->back_value = $request->back_value;
        $football->lay_value = $request->lay_value;
        $football->match_type = $request->match_type;
        $football->stake = $request->stake;
        $football->status = 1;
        $football->back_status = 1;
        $football->lay_status = 1;
        $football->game_id = $request->game_id;
        $football->save();
        
        $game_id = $request->game_id;
        $footballs = FootballMatch::where('game_id',$game_id)->orderBy('id','desc')->get();

        return view('agent.football.create-football-match',compact('game_id','footballs'))->with('message', 'Match created successfully');
    }
    public function submitFootballMatchScore (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name_a' => 'required',
            'team_name_b' => 'required',
            'score_a' => 'required',
            'score_b' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
 // Attempt to find an existing record
        $matchScore = FootballMatchScore::where('game_id', $request->game_id)->first();

        if ($matchScore) {
            // Update the existing record
            $matchScore->update([
                'game_id' => $request->game_id,
                'team_name_a' => $request->team_name_a,
                'team_name_b' => $request->team_name_b,
                'score_a' => $request->score_a,
                'score_b' => $request->score_b
            ]);
        } else {
            // Insert a new record
            FootballMatchScore::create([
                'game_id' => $request->game_id,
                'team_name_a' => $request->team_name_a,
                'team_name_b' => $request->team_name_b,
                'score_a' => $request->score_a,
                'score_b' => $request->score_b
            ]);
        }

        return redirect()->back();
    }
    
    public function updateFootballMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $football = FootballMatch::findOrFail($request->match_id);
        $football->team_name = $request->team_name;
        $football->back_value = $request->back_value;
        $football->lay_value = $request->lay_value;
        $football->match_type = $request->match_type;
        $football->save();
        
        $game_id = $request->game_id;
        $footballs = FootballMatch::where('game_id',$game_id)->orderBy('id','desc')->get();

       
        return view('agent.football.create-football-match',compact('game_id','footballs'))->with('message', 'Match updated successfully');
    }
    
    public function activateBackStatus($id){
        $football = FootballMatch::findOrFail($id);
        $football->back_status = 1;
        $football->save();
        return redirect()->back();
    }
    public function deactivateBackStatus($id){
        $football = FootballMatch::findOrFail($id);
        $football->back_status = 0;
        $football->save();
        return redirect()->back();
    }
    public function activateLayStatus($id){
        $football = FootballMatch::findOrFail($id);
        $football->lay_status = 1;
        $football->save();
        return redirect()->back();
    }
    public function deactivateLayStatus($id){
        $football = FootballMatch::findOrFail($id);
        $football->lay_status = 0;
        $football->save();
        return redirect()->back();
    }
    
    public function activateFootballGameStatus($id){
        $football = FootballGame::findOrFail($id);
        $football->status = 1;
        $football->save();
        return redirect()->back();
    }
    public function deactivateFootballGameStatus($id){
        $football = FootballGame::findOrFail($id);
        $football->status = 0;
        $football->save();
        return redirect()->back();
    }
    
    public function changeMatchStatus($id,$gameid){
        $football = FootballMatch::findOrFail($id);
        $currentStatus = $football->status;
        $win_loss = $football->win_loss;
        
        return view('agent.football.change-football-match-status',compact('id','gameid','currentStatus','win_loss','football'));
    }
    
     public function changeMatchStatusSubmit (Request $request) {
         
        
          $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://crickekbuz.art/api/profit_loss/'.$request->id.'/football/'.$request->win_loss,
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
          CURLOPT_URL => 'https://newsilver.art/api/profit_loss/'.$request->id.'/football/'.$request->win_loss,
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
          CURLOPT_URL => 'https://gold365.art/api/profit_loss/'.$request->id.'/football/'.$request->win_loss,
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
          CURLOPT_URL => 'https://allpanel.art/api/profit_loss/'.$request->id.'/football/'.$request->win_loss,
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
          CURLOPT_URL => 'https://laserclub.art/api/profit_loss/'.$request->id.'/football/'.$request->win_loss,
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
        
        
        
        $validator = Validator::make($request->all(),[
            'status' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $football = FootballMatch::findOrFail($request->id);
        $football->status = $request->status;
        if(isset($request->win_loss)){
            $football->win_loss = $request->win_loss; 
                   
            // $winnerSelection = FootballPlaceBet::where('match_id',$football->id)->where('bet_result',null)->where('back_lay',$request->win_loss)
            //                       ->join('admins', 'football_place_bet.user_id', '=', 'admins.id')
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
         
        }
        $football->save();

        return redirect()->route('admin-football-game-list')->with('message', 'Match status changed successfully');
    }
    
    public function deleteFootballMatch($id){
        $cricket = FootballMatch::findOrFail($id);
        $cricket->delete();
        return redirect()->back()->with('message','Match deleted successfully');
    }
    
    public function getGameListApi(){
          $today = Carbon::today();
        $tomorrow1 = Carbon::tomorrow();
        $fourDaysLater = $tomorrow1->addDays(5); // Date 4 days from tomorrow
        $fourDaysprevious = $today->subDays(5); // Date 4 days from yesturday
       
        // Format the date into SQL timestamp format
        $sqlTimestamp = $fourDaysLater->format('Y-m-d H:i:s');
        
 
        $footballs = FootballGame::whereDate('run_date_time','>=', $fourDaysprevious)
            ->WhereDate('run_date_time','<=', $fourDaysLater)
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')  // Order by datetimeGMT in ascending order
            ->get();
        // $today = Carbon::today();
        // $tomorrow = Carbon::tomorrow();
        // //   $crickets = CricketGame::whereDate('datetimeGMT', $today)
        // //     ->orWhereDate('datetimeGMT', $tomorrow)
        // //     ->with('match_list')
        // //     ->orderBy('datetimeGMT', 'asc')  // Order by datetimeGMT in ascending order
        // //     ->get();
        //     // $footballs = FootballGame::whereBetween('run_date_time', [$today, $tomorrow->endOfDay()])->with('match_list')->orderBy('run_date_time', 'asc')->get();
        //     $footballs = FootballGame::with('match_list')->orderBy('run_date_time', 'asc')->get();
        // // dd($footballs);
        return response()->json($footballs);
    }
    
    public function getMatchListApi($game_id){
        $match_odds = FootballMatch::where('game_id',$game_id)->where('match_type','match_odds')->where('status',1)->orderBy('id','desc')->get();
        $over_under_0_point_5_goals = FootballMatch::where('game_id',$game_id)->where('match_type','over_under_0_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_1_point_5_goals = FootballMatch::where('game_id',$game_id)->where('match_type','over_under_1_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_2_point_5_goals = FootballMatch::where('game_id',$game_id)->where('match_type','over_under_2_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_3_point_5_goals = FootballMatch::where('game_id',$game_id)->where('match_type','over_under_3_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        
        // dd($footballs);
        return response()->json([
            'match_odds'=>$match_odds,
            'over_under_0_point_5_goals'=>$over_under_0_point_5_goals,
            'over_under_1_point_5_goals'=>$over_under_1_point_5_goals,
            'over_under_2_point_5_goals'=>$over_under_2_point_5_goals,
            'over_under_3_point_5_goals'=>$over_under_3_point_5_goals
        ]);
    }
    
    public function getSingleGameByIdApi($game_id){
        $game = FootballGame::findOrFail($game_id);
        // dd($footballs);
        return response()->json($game);
    }
    
    public function placeBetForFootballApi(Request $request){
        // return $request->all();
        $placeBet = new FootballPlaceBet();
        $placeBet->match_id = $request['match_id'];
        $placeBet->bet_odds = $request['bet_odds'];
        $placeBet->bet_stake = $request['bet_stake'];
        $placeBet->back_lay = $request['back_lay'];
        $placeBet->bet_profit = $request['bet_profit'];
        $placeBet->user_id = $request['user_id'];
        $placeBet->team_name = $request['bet_team_name'];
        $placeBet->save();
        
        $played_matches = FootballPlaceBet::where('user_id',$request['user_id'])->where('bet_result',null)->get();
        
        return response()->json([
            "place_bet_id" => $placeBet->id,
            "bet_request" => $request->all(),
            "played_matches" => $played_matches
        ]);
    }
    
    public function getFootballMatchResultApi(){
        $result = FootballMatch::whereNotNull('win_loss')->orderBy('id','desc')->first();
        
        return response()->json([
            'match_result' => $result    
        ]);
    }

}
