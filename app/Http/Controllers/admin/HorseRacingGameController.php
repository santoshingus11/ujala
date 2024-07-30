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
use App\Models\HorseRacingGame;
use App\Models\HorseRacingMatch;
use App\Models\HorseRacingTimeSlot;


class HorseRacingGameController extends Controller
{
    // horseracing Game functions goes here
    
    public function adminHorseRacingGameList() {
        $horseracings = HorseRacingGame::orderBy('id','desc')->paginate(10);
        return view('agent.horseracing.admin_horseracing_game_list',compact('horseracings'));
    }
    
    
     public function adminHorseRacingRemoveGame($id){
        $check = HorseRacingGame::find($id);
        if($check){
                $timeslots = HorseRacingTimeSlot::where('game_id',$id)->get();
                foreach($timeslots as $t){
                    $t->delete();
                }
                $check->delete();
                return back()->with('success',"Deleted Successfully!");
        }else{
            return back()->with('fail','Something Went Wrong!');
        }
            
        // }else{
        //     return back()->with('fail','Record Not Found!');
        // }
    }
    public function adminHorseRacingGameUpdate(Request $request){
        $check = HorseRacingGame::find($request->match_id);
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
    
    
    
    
    
    
    
    
    public function createHorseRacingGame(){
        
        return view('agent.horseracing.create-horseracing-game');
    }
    
    public function submitHorseRacingGame (Request $request) {
        $validator = Validator::make($request->all(),[
            'game_title' => 'required',
            'run_date_time'=>'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $horseracing = new HorseRacingGame();
        $horseracing->game_title = $request->game_title;
        $horseracing->run_date_time = $request->run_date_time;
        $horseracing->status = 1;
        $horseracing->save();

        return redirect()->route('admin-horseracing-game-list')->with('message', 'Game created successfully');
    }
    
    public function createHorseRacingGameTimeSlot(){
        $games = HorseRacingGame::where('status',1)->orderBy('id','desc')->get();
        return view('agent.horseracing.create-horseracing-time-slot',compact('games'));
    }
    
    public function submitHorseRacingGameTimeSlot (Request $request) {
       
        $validator = Validator::make($request->all(),[
            'time_slot' => 'required',
            'game_id' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $horseracing = new HorseRacingTimeSlot();
        $horseracing->time_slot = $request->time_slot;
        $horseracing->game_id = $request->game_id;
        $horseracing->status = 1;
        $horseracing->save();

        return redirect()->route('admin-horseracing-time-slot-list')->with('message', 'Time slot created successfully');
    }
    
        
    public function adminHorseRacingGameTimeSlotList() {
        $horseracings = HorseRacingTimeSlot::orderBy('id','desc')->paginate(10);
        return view('agent.horseracing.admin_horseracing_time_slot_list',compact('horseracings'));
    }
    
    public function activateHorseRacingTimeslotStatus($id){
        $horseracing = HorseRacingTimeSlot::findOrFail($id);
        $horseracing->status = 1;
        $horseracing->save();
        return redirect()->back();
    }
    public function deactivateHorseRacingTimeslotStatus($id){
        $horseracing = HorseRacingTimeSlot::findOrFail($id);
        $horseracing->status = 0;
        $horseracing->save();
        return redirect()->back();
    }

     
    public function createHorseRacingMatch($id){
        $time_slot_id = $id;
        $horseracings = HorseRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();
        return view('agent.horseracing.create-horseracing-match',compact('time_slot_id','horseracings'));
    }
    
    public function submitHorseRacingMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $horseracing = new HorseRacingMatch();
        $horseracing->team_name = $request->team_name;
        $horseracing->back_value = $request->back_value;
        $horseracing->lay_value = $request->lay_value;
        $horseracing->match_type = $request->match_type;
        $horseracing->status = 1;
        $horseracing->back_status = 1;
        $horseracing->lay_status = 1;
        $horseracing->time_slot_id = $request->time_slot_id;
        $horseracing->save();
        
        $time_slot_id = $request->time_slot_id;
        $horseracings = HorseRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();

        return view('agent.horseracing.create-horseracing-match',compact('time_slot_id','horseracings'))->with('message', 'Match created successfully');
    }
    
    public function updateHorseRacingMatch (Request $request) {
        $validator = Validator::make($request->all(),[
            'team_name' => 'required',
            'back_value' => 'required',
            'lay_value' => 'required',
            'match_type' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $horseracing = HorseRacingMatch::findOrFail($request->match_id);
        $horseracing->team_name = $request->team_name;
        $horseracing->back_value = $request->back_value;
        $horseracing->lay_value = $request->lay_value;
        $horseracing->match_type = $request->match_type;
        $horseracing->save();
        
        $time_slot_id = $request->time_slot_id;
        $horseracings = HorseRacingMatch::where('time_slot_id',$time_slot_id)->orderBy('id','desc')->get();

       
        return view('agent.horseracing.create-horseracing-match',compact('time_slot_id','horseracings'))->with('message', 'Match updated successfully');
    }
    
    public function activateBackStatus($id){
        $horseracing = HorseRacingMatch::findOrFail($id);
        $horseracing->back_status = 1;
        $horseracing->save();
        return redirect()->back();
    }
    public function deactivateBackStatus($id){
        $horseracing = HorseRacingMatch::findOrFail($id);
        $horseracing->back_status = 0;
        $horseracing->save();
        return redirect()->back();
    }
    public function activateLayStatus($id){
        $horseracing = HorseRacingMatch::findOrFail($id);
        $horseracing->lay_status = 1;
        $horseracing->save();
        return redirect()->back();
    }
    public function deactivateLayStatus($id){
        $horseracing = HorseRacingMatch::findOrFail($id);
        $horseracing->lay_status = 0;
        $horseracing->save();
        return redirect()->back();
    }
    
    public function activateHorseRacingGameStatus($id){
        $horseracing = HorseRacingGame::findOrFail($id);
        $horseracing->status = 1;
        $horseracing->save();
        return redirect()->back();
    }
    public function deactivateHorseRacingGameStatus($id){
        $horseracing = HorseRacingGame::findOrFail($id);
        $horseracing->status = 0;
        $horseracing->save();
        return redirect()->back();
    }
    
    public function changeMatchStatus($id,$time_slot_id){
        $horseracing = HorseRacingMatch::findOrFail($id);
        $currentStatus = $horseracing->status;
        $win_loss = $horseracing->win_loss;
        
        return view('agent.horseracing.change-horseracing-match-status',compact('id','time_slot_id','currentStatus','win_loss','horseracing'));
    }
    
     public function changeMatchStatusSubmit (Request $request) {
         
            $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://crickekbuz.art/api/profit_loss/'.$request->id.'/horse/'.$request->win_loss,
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
          CURLOPT_URL => 'https://newsilver.art/api/profit_loss/'.$request->id.'/horse/'.$request->win_loss,
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
          CURLOPT_URL => 'https://gold365.art/api/profit_loss/'.$request->id.'/horse/'.$request->win_loss,
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
          CURLOPT_URL => 'https://allpanel.art/api/profit_loss/'.$request->id.'/horse/'.$request->win_loss,
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
          CURLOPT_URL => 'https://laserclub.art/api/profit_loss/'.$request->id.'/horse/'.$request->win_loss,
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

        $horseracing = HorseRacingMatch::findOrFail($request->id);
        $horseracing->status = $request->status;
        if(isset($request->win_loss)){
            $horseracing->win_loss = $request->win_loss; 
                   
            // $winnerSelection = horseracingPlaceBet::where('match_id',$horseracing->id)->where('bet_result',null)->where('back_lay',$request->win_loss)
            //                       ->join('admins', 'horseracing_place_bet.user_id', '=', 'admins.id')
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
        $horseracing->save();

        return redirect()->route('admin-horseracing-game-list')->with('message', 'Match status changed successfully');
    }
    
    public function deleteHorseRacingMatch($id){
        $cricket = HorseRacingMatch::findOrFail($id);
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
        
 
        $horseracings = HorseRacingGame::whereDate('run_date_time','>=', $fourDaysprevious)
            ->WhereDate('run_date_time','<=', $fourDaysLater)
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')  // Order by datetimeGMT in ascending order
            ->get();
        // $today = date('Y-m-d');
        // $horseracings = HorseRacingGame::whereDate('run_date_time','>=',$today)->orderBy('id','desc')->with('time_slots')->get();
        // dd($horseracings);
        return response()->json($horseracings);
    }
    
    public function getMatchListApi($time_slot_id){
        $match_odds = HorseRacingMatch::where('time_slot_id',$time_slot_id)->where('match_type','match_odds')->where('status',1)->orderBy('id','desc')->get();
        
        // dd($horseracings);
        return response()->json([
            'match_odds'=>$match_odds
          ]);
    }
    
    public function getSingleGameByIdApi($time_slot_id){
        $timeslot = HorseRacingTimeSlot::findOrFail($time_slot_id);
        if(!empty($timeslot)){
            $game_id = $timeslot->game_id;
            $game = HorseRacingGame::findOrFail($game_id);
        }else{
            $game = [];
        }
        // dd($timeslots->game_id);
        return response()->json($game);
    }
    
    public function placeBetForHorseRacingApi(Request $request){
        // return $request->all();
        // $placeBet = new horseracingPlaceBet();
        // $placeBet->match_id = $request['match_id'];
        // $placeBet->bet_odds = $request['bet_odds'];
        // $placeBet->bet_stake = $request['bet_stake'];
        // $placeBet->back_lay = $request['back_lay'];
        // $placeBet->bet_profit = $request['bet_profit'];
        // $placeBet->user_id = $request['user_id'];
        // $placeBet->team_name = $request['bet_team_name'];
        // $placeBet->save();
        
        // $played_matches = horseracingPlaceBet::where('user_id',$request['user_id'])->where('bet_result',null)->get();
        
        // return response()->json([
        //     "place_bet_id" => $placeBet->id,
        //     "bet_request" => $request->all(),
        //     "played_matches" => $played_matches
        // ]);
    }
    
    public function getHorseRacingMatchResultApi(){
        $result = HorseRacingMatch::whereNotNull('win_loss')->orderBy('id','desc')->first();
        
        return response()->json([
            'match_result' => $result    
        ]);
    }

}
