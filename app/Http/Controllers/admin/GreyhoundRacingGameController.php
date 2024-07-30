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
use App\Models\GreyhoundRacingGame;
use App\Models\GreyhoundRacingMatch;
use App\Models\GreyhoundRacingTimeSlot;


class GreyhoundRacingGameController extends Controller
{
    // greyhoundracing Game functions goes here
    
    public function adminGreyhoundRacingGameList() {
        $greyhoundracings = GreyhoundRacingGame::orderBy('id','desc')->paginate(10);
        return view('agent.greyhoundracing.admin_greyhoundracing_game_list',compact('greyhoundracings'));
    }
    
    
    
     public function adminGreyhoundRacingRemoveGame($id){
        $check = GreyhoundRacingGame::find($id);
        if($check){
            $timeslots = GreyhoundRacingTimeSlot::where('game_id',$id)->get();
            foreach($timeslots as $t){
                $t->delete();
            }
            $check->delete();
            return back()->with('success',"Deleted Successfully!");
        }else{
            return back()->with('fail','Something Went Wrong!');
        }
            
    }
    public function adminGreyhoundRacingGameUpdate(Request $request){
        $check = GreyhoundRacingGame::find($request->match_id);
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
    
    
    
    
    
    
    public function createGreyhoundRacingGame(){
        
        return view('agent.greyhoundracing.create-greyhoundracing-game');
    }
    
    public function submitGreyhoundRacingGame (Request $request) {
        $validator = Validator::make($request->all(),[
            'game_title' => 'required',
            'run_date_time'=>'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $greyhoundracing = new GreyhoundRacingGame();
        $greyhoundracing->game_title = $request->game_title;
        $greyhoundracing->run_date_time = $request->run_date_time;
        $greyhoundracing->status = 1;
        $greyhoundracing->save();

        return redirect()->route('admin-greyhoundracing-game-list')->with('message', 'Game created successfully');
    }
    
    public function createGreyhoundRacingGameTimeSlot(){
        $games = GreyhoundRacingGame::where('status',1)->orderBy('id','desc')->get();
        return view('agent.greyhoundracing.create-greyhoundracing-time-slot',compact('games'));
    }
    
    public function submitGreyhoundRacingGameTimeSlot (Request $request) {
        $validator = Validator::make($request->all(),[
            'time_slot' => 'required',
            'game_id' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $greyhoundracing = new GreyhoundRacingTimeSlot();
        $greyhoundracing->time_slot = $request->time_slot;
        $greyhoundracing->game_id = $request->game_id;
        $greyhoundracing->status = 1;
        $greyhoundracing->save();

        return redirect()->route('admin-greyhoundracing-time-slot-list')->with('message', 'Time slot created successfully');
    }
        
    public function adminGreyhoundRacingGameTimeSlotList() {
        $greyhoundracings = GreyhoundRacingTimeSlot::orderBy('id','desc')->paginate(10);
        return view('agent.greyhoundracing.admin_greyhoundracing_time_slot_list',compact('greyhoundracings'));
    }
    
    public function activateGreyhoundRacingTimeslotStatus($id){
        $horseracing = GreyhoundRacingTimeSlot::findOrFail($id);
        $horseracing->status = 1;
        $horseracing->save();
        return redirect()->back();
    }
    public function deactivateGreyhoundRacingTimeslotStatus($id){
        $horseracing = GreyhoundRacingTimeSlot::findOrFail($id);
        $horseracing->status = 0;
        $horseracing->save();
        return redirect()->back();
    }
     
    public function createGreyhoundRacingMatch($id){
        $time_slot_id = $id;
        $greyhoundracings = GreyhoundRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();
        return view('agent.greyhoundracing.create-greyhoundracing-match',compact('time_slot_id','greyhoundracings'));
    }
    
    public function submitGreyhoundRacingMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $greyhoundracing = new GreyhoundRacingMatch();
        $greyhoundracing->team_name = $request->team_name;
        $greyhoundracing->back_value = $request->back_value;
        $greyhoundracing->lay_value = $request->lay_value;
        $greyhoundracing->match_type = $request->match_type;
        $greyhoundracing->status = 1;
        $greyhoundracing->back_status = 1;
        $greyhoundracing->lay_status = 1;
        $greyhoundracing->time_slot_id = $request->time_slot_id;
        $greyhoundracing->save();
        
        $time_slot_id = $request->time_slot_id;
        $greyhoundracings = GreyhoundRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();

        return view('agent.greyhoundracing.create-greyhoundracing-match',compact('time_slot_id','greyhoundracings'))->with('message', 'Match created successfully');
    }
    
    public function updateGreyhoundRacingMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $greyhoundracing = GreyhoundRacingMatch::findOrFail($request->match_id);
        $greyhoundracing->team_name = $request->team_name;
        $greyhoundracing->back_value = $request->back_value;
        $greyhoundracing->lay_value = $request->lay_value;
        $greyhoundracing->match_type = $request->match_type;
        $greyhoundracing->save();
        
        $time_slot_id = $request->time_slot_id;
        $greyhoundracings = GreyhoundRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();

       
        return view('agent.greyhoundracing.create-greyhoundracing-match',compact('time_slot_id','greyhoundracings'))->with('message', 'Match updated successfully');
    }
    
    public function activateBackStatus($id){
        $greyhoundracing = GreyhoundRacingMatch::findOrFail($id);
        $greyhoundracing->back_status = 1;
        $greyhoundracing->save();
        return redirect()->back();
    }
    public function deactivateBackStatus($id){
        $greyhoundracing = GreyhoundRacingMatch::findOrFail($id);
        $greyhoundracing->back_status = 0;
        $greyhoundracing->save();
        return redirect()->back();
    }
    public function activateLayStatus($id){
        $greyhoundracing = GreyhoundRacingMatch::findOrFail($id);
        $greyhoundracing->lay_status = 1;
        $greyhoundracing->save();
        return redirect()->back();
    }
    public function deactivateLayStatus($id){
        $greyhoundracing = GreyhoundRacingMatch::findOrFail($id);
        $greyhoundracing->lay_status = 0;
        $greyhoundracing->save();
        return redirect()->back();
    }
    
    public function activateGreyhoundRacingGameStatus($id){
        $greyhoundracing = GreyhoundRacingGame::findOrFail($id);
        $greyhoundracing->status = 1;
        $greyhoundracing->save();
        return redirect()->back();
    }
    public function deactivateGreyhoundRacingGameStatus($id){
        $greyhoundracing = GreyhoundRacingGame::findOrFail($id);
        $greyhoundracing->status = 0;
        $greyhoundracing->save();
        return redirect()->back();
    }
    
    public function changeMatchStatus($id,$time_slot_id){
        $greyhoundracing = GreyhoundRacingMatch::findOrFail($id);
        $currentStatus = $greyhoundracing->status;
        $win_loss = $greyhoundracing->win_loss;
        
        return view('agent.greyhoundracing.change-greyhoundracing-match-status',compact('id','time_slot_id','currentStatus','win_loss','greyhoundracing'));
    }
    
     public function changeMatchStatusSubmit (Request $request) {
         
            $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://crickekbuz.art/api/profit_loss/'.$request->id.'/greyhound/'.$request->win_loss,
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
          CURLOPT_URL => 'https://newsilver.art/api/profit_loss/'.$request->id.'/greyhound/'.$request->win_loss,
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
          CURLOPT_URL => 'https://gold365.art/api/profit_loss/'.$request->id.'/greyhound/'.$request->win_loss,
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
          CURLOPT_URL => 'https://allpanel.art/api/profit_loss/'.$request->id.'/greyhound/'.$request->win_loss,
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
          CURLOPT_URL => 'https://laserclub.art/api/profit_loss/'.$request->id.'/greyhound/'.$request->win_loss,
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

        $greyhoundracing = GreyhoundRacingMatch::findOrFail($request->id);
        $greyhoundracing->status = $request->status;
        if(isset($request->win_loss)){
            $greyhoundracing->win_loss = $request->win_loss; 
                   
            // $winnerSelection = GreyhoundRacingPlaceBet::where('match_id',$greyhoundracing->id)->where('bet_result',null)->where('back_lay',$request->win_loss)
            //                       ->join('admins', 'greyhoundracing_place_bet.user_id', '=', 'admins.id')
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
        $greyhoundracing->save();

        return redirect()->route('admin-greyhoundracing-game-list')->with('message', 'Match status changed successfully');
    }
    
     public function deleteGreyhoundRacingMatch($id){
        $cricket = GreyhoundRacingMatch::findOrFail($id);
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
        
 
        $greyhoundracings = GreyhoundRacingGame::whereDate('run_date_time','>=', $fourDaysprevious)
            ->WhereDate('run_date_time','<=', $fourDaysLater)
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')  // Order by datetimeGMT in ascending order
            ->get();
        // $today = date('Y-m-d');
        // $greyhoundracings = GreyhoundRacingGame::whereDate('run_date_time','>=',$today)->orderBy('id','desc')->with('time_slots')->get();
        // dd($greyhoundracings);
        return response()->json($greyhoundracings);
    }
    
    public function getMatchListApi($time_slot_id){
        $match_odds = GreyhoundRacingMatch::where('time_slot_id',$time_slot_id)->where('match_type','match_odds')->where('status',1)->orderBy('id','desc')->get();
         
        // dd($greyhoundracings);
        return response()->json([
            'match_odds'=>$match_odds
           
        ]);
    }
    
    public function getSingleGameByIdApi($time_slot_id){
         $timeslot = GreyhoundRacingTimeSlot::findOrFail($time_slot_id);
        if(!empty($timeslot)){
            $game_id = $timeslot->game_id;
            $game = GreyhoundRacingGame::findOrFail($game_id);
        }else{
            $game = [];
        }
        // dd($timeslots->game_id);
        return response()->json($game);
    }
    
    public function placeBetForGreyhoundRacingApi(Request $request){
        // return $request->all();
        // $placeBet = new GreyhoundRacingPlaceBet();
        // $placeBet->match_id = $request['match_id'];
        // $placeBet->bet_odds = $request['bet_odds'];
        // $placeBet->bet_stake = $request['bet_stake'];
        // $placeBet->back_lay = $request['back_lay'];
        // $placeBet->bet_profit = $request['bet_profit'];
        // $placeBet->user_id = $request['user_id'];
        // $placeBet->team_name = $request['bet_team_name'];
        // $placeBet->save();
        
        // $played_matches = GreyhoundRacingPlaceBet::where('user_id',$request['user_id'])->where('bet_result',null)->get();
        
        // return response()->json([
        //     "place_bet_id" => $placeBet->id,
        //     "bet_request" => $request->all(),
        //     "played_matches" => $played_matches
        // ]);
    }
    
    public function getGreyhoundRacingMatchResultApi(){
        $result = GreyhoundRacingMatch::whereNotNull('win_loss')->orderBy('id','desc')->first();
        
        return response()->json([
            'match_result' => $result    
        ]);
    }

}
