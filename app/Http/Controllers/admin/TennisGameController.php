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
use App\Models\TennisGame;
use App\Models\TennisMatchScore;
use App\Models\TennisMatch;


class TennisGameController extends Controller
{
    // tennis Game functions goes here
    
    public function adminTennisGameList() {
        $tennises = TennisGame::orderBy('id','desc')->paginate(10);
        return view('agent.tennis.admin_tennis_game_list',compact('tennises'));
    }
    
    
    
    public function adminTennisRemoveGame($id){
        $check = TennisGame::find($id);
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
    public function adminTennisGameUpdate(Request $request){
        $check = TennisGame::find($request->match_id);
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
    
    
    
    
    public function createTennisGame(){
        
        return view('agent.tennis.create-tennis-game');
    }
    
    public function submitTennisGame (Request $request) {
        $validator = Validator::make($request->all(),[
            'game_title' => 'required',
            'run_date_time'=>'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tennis = new TennisGame();
        $tennis->game_title = $request->game_title;
        $tennis->run_date_time = $request->run_date_time;
        $tennis->status = 1;
        $tennis->save();

        return redirect()->route('admin-tennis-game-list')->with('message', 'Game created successfully');
    }
     
    public function createTennisMatch($id){
        $game_id = $id;
        $tennises = TennisMatch::where('game_id',$game_id)->orderBy('id','desc')->get();
         $footballscore = TennisMatchScore::where('game_id',$game_id)->first();
        return view('agent.tennis.create-tennis-match',compact('game_id','tennises','footballscore'));
    }
    
    public function submitTennisMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tennis = new TennisMatch();
        $tennis->team_name = $request->team_name;
        $tennis->back_value = $request->back_value;
        $tennis->lay_value = $request->lay_value;
        $tennis->match_type = $request->match_type;
        $tennis->stake = $request->stake;
        $tennis->status = 1;
        $tennis->back_status = 1;
        $tennis->lay_status = 1;
        $tennis->game_id = $request->game_id;
        $tennis->save();
        
        $game_id = $request->game_id;
        $tennises = TennisMatch::where('game_id',$game_id)->orderBy('id','desc')->get();

        return view('agent.tennis.create-tennis-match',compact('game_id','tennises'))->with('message', 'Match created successfully');
    }
   
   
        public function submitTennisMatchScore (Request $request) {
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
        $matchScore = TennisMatchScore::where('game_id', $request->game_id)->first();

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
            TennisMatchScore::create([
                'game_id' => $request->game_id,
                'team_name_a' => $request->team_name_a,
                'team_name_b' => $request->team_name_b,
                'score_a' => $request->score_a,
                'score_b' => $request->score_b
            ]);
        }

        return redirect()->back();
    }
    public function updateTennisMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tennis = TennisMatch::findOrFail($request->match_id);
        $tennis->team_name = $request->team_name;
        $tennis->back_value = $request->back_value;
        $tennis->lay_value = $request->lay_value;
        $tennis->match_type = $request->match_type;
        $tennis->save();
        
        $game_id = $request->game_id;
        $tennises = TennisMatch::where('game_id',$game_id)->orderBy('id','desc')->get();

       
        return view('agent.tennis.create-tennis-match',compact('game_id','tennises'))->with('message', 'Match updated successfully');
    }
    
    public function activateBackStatus($id){
        $tennis = TennisMatch::findOrFail($id);
        $tennis->back_status = 1;
        $tennis->save();
        return redirect()->back();
    }
    public function deactivateBackStatus($id){
        $tennis = TennisMatch::findOrFail($id);
        $tennis->back_status = 0;
        $tennis->save();
        return redirect()->back();
    }
    public function activateLayStatus($id){
        $tennis = TennisMatch::findOrFail($id);
        $tennis->lay_status = 1;
        $tennis->save();
        return redirect()->back();
    }
    public function deactivateLayStatus($id){
        $tennis = TennisMatch::findOrFail($id);
        $tennis->lay_status = 0;
        $tennis->save();
        return redirect()->back();
    }
    
    public function activateTennisGameStatus($id){
        $tennis = TennisGame::findOrFail($id);
        $tennis->status = 1;
        $tennis->save();
        return redirect()->back();
    }
    public function deactivateTennisGameStatus($id){
        $tennis = TennisGame::findOrFail($id);
        $tennis->status = 0;
        $tennis->save();
        return redirect()->back();
    }
    
    public function changeMatchStatus($id,$gameid){
        $tennis = TennisMatch::findOrFail($id);
        $currentStatus = $tennis->status;
        $win_loss = $tennis->win_loss;
        
        return view('agent.tennis.change-tennis-match-status',compact('id','gameid','currentStatus','win_loss','tennis'));
    }
    
     public function changeMatchStatusSubmit (Request $request) {
         
            $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://crickekbuz.art/api/profit_loss/'.$request->id.'/tennis/'.$request->win_loss,
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
          CURLOPT_URL => 'https://newsilver.art/api/profit_loss/'.$request->id.'/tennis/'.$request->win_loss,
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
          CURLOPT_URL => 'https://gold365.art/api/profit_loss/'.$request->id.'/tennis/'.$request->win_loss,
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
          CURLOPT_URL => 'https://allpanel.art/api/profit_loss/'.$request->id.'/tennis/'.$request->win_loss,
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
          CURLOPT_URL => 'https://laserclub.art/api/profit_loss/'.$request->id.'/tennis/'.$request->win_loss,
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

        $tennis = TennisMatch::findOrFail($request->id);
        $tennis->status = $request->status;
        if(isset($request->win_loss)){
            $tennis->win_loss = $request->win_loss; 
                   
            // $winnerSelection = TennisPlaceBet::where('match_id',$tennis->id)->where('bet_result',null)->where('back_lay',$request->win_loss)
            //                       ->join('admins', 'tennis_place_bet.user_id', '=', 'admins.id')
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
        $tennis->save();

        return redirect()->route('admin-tennis-game-list')->with('message', 'Match status changed successfully');
    }
    
    public function deleteTennisMatch($id){
        $cricket = TennisMatch::findOrFail($id);
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
        
 
        $tennises = TennisGame::whereDate('run_date_time','>=', $fourDaysprevious)
            ->WhereDate('run_date_time','<=', $fourDaysLater)
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')  // Order by datetimeGMT in ascending order
            ->get();
        // $today = date('Y-m-d');
        // $tennises = TennisGame::whereDate('run_date_time','>=',$today)->orderBy('id','desc')->with('match_list')->get();
        // dd($tennises);
        return response()->json($tennises);
    }
    
    public function getMatchListApi($game_id){
        $match_odds = TennisMatch::where('game_id',$game_id)->where('match_type','match_odds')->where('status',1)->orderBy('id','desc')->get();
        $over_under_0_point_5_goals = TennisMatch::where('game_id',$game_id)->where('match_type','over_under_0_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_1_point_5_goals = TennisMatch::where('game_id',$game_id)->where('match_type','over_under_1_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_2_point_5_goals = TennisMatch::where('game_id',$game_id)->where('match_type','over_under_2_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        $over_under_3_point_5_goals = TennisMatch::where('game_id',$game_id)->where('match_type','over_under_3_point_5_goals')->where('status',1)->orderBy('id','desc')->get();
        
        // dd($tennises);
        return response()->json([
            'match_odds'=>$match_odds,
            'over_under_0_point_5_goals'=>$over_under_0_point_5_goals,
            'over_under_1_point_5_goals'=>$over_under_1_point_5_goals,
            'over_under_2_point_5_goals'=>$over_under_2_point_5_goals,
            'over_under_3_point_5_goals'=>$over_under_3_point_5_goals
        ]);
    }
    
    public function getSingleGameByIdApi($game_id){
        $game = TennisGame::findOrFail($game_id);
        // dd($tennises);
        return response()->json($game);
    }
    
    public function placeBetForTennisApi(Request $request){
        // return $request->all();
        // $placeBet = new tennisPlaceBet();
        // $placeBet->match_id = $request['match_id'];
        // $placeBet->bet_odds = $request['bet_odds'];
        // $placeBet->bet_stake = $request['bet_stake'];
        // $placeBet->back_lay = $request['back_lay'];
        // $placeBet->bet_profit = $request['bet_profit'];
        // $placeBet->user_id = $request['user_id'];
        // $placeBet->team_name = $request['bet_team_name'];
        // $placeBet->save();
        
        // $played_matches = TennisPlaceBet::where('user_id',$request['user_id'])->where('bet_result',null)->get();
        
        // return response()->json([
        //     "place_bet_id" => $placeBet->id,
        //     "bet_request" => $request->all(),
        //     "played_matches" => $played_matches
        // ]);
    }
    
    public function getTennisMatchResultApi(){
        $result = TennisMatch::whereNotNull('win_loss')->orderBy('id','desc')->first();
        
        return response()->json([
            'match_result' => $result    
        ]);
    }

}
