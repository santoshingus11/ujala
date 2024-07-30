<?php

namespace App\Http\Controllers\admin;

use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use App\Models\CricketGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ScoreCard;
use App\Models\Series;
use App\Models\FootballGame;
use Carbon\Carbon;

class ZapiController extends Controller
{
    public function getAllSeries(){
       $series =  $this->getSeriesData();
        if(count($series)>0){
            foreach($series as $s){
                $ser = Series::where('series_id',$s->id)->first();
                if(!$ser){
                    Series::create([
                                        'series_id' => $s->id,
                                        'name' => $s->name
                                    ]);
                }
            }
        }
        
        return response()->json(['status'=>'Success','message'=>'Records Saved Successfully!','data'=>[]]);
        
    }
    public function storeSeriesGames(){
         $this->getAllSeries();
    }
    
    public function storeCricketGames(){
       $series = Series::all();
      $matches = $this->getResults();
      try{
              if(count($matches)>0){
                  foreach($matches as $match){
                      $mt = CricketGame::where('match_id',$match['match_id'])->first();
                      if(!$mt){
                          CricketGame::insert($match);
                      }else{
                          $mt->update($match);
                      }
                  }
              }
         
          return response()->json(['status'=>'Success','message'=>'Records Saved Successfully!','data'=>[]]);
              }catch (\Throwable $th) {
                return response()->json(['status'=>'fail','message'=>'Something Went Wrong!','data'=>$th->getMessage()]);
            }
     
       
    }
    
    public function liveScoreCard(){
        $matches = CricketGame::all();
        if(count($matches)>0){
            foreach($matches as $match){
                if(isset($match->match_id)){
                    $this->matchInfo($match);
                }
            }
            return response()->json(['status'=>'Success','message'=>'Records Saved Successfully!','data'=>[]]);
        }else{
            return response()->json(['status'=>'fail','message'=>'Records Not Found!','data'=>[]]);
        }
    }
    
    protected function matchInfo($match):void
    {
       
               $result = $this->getMatchInfo_mainvoid($match->match_id);
                if(isset($result['t1score'])){
                    $match->update(['status'=>1]);
                }
               $score = ScoreCard::where('match_id',$match->id)->first();
               if($score){
                   $score->update($result);
               }else{
                   $result['match_id'] = $match->id;
                   ScoreCard::insert($result);
               }
          
    }
    
    public function apiGetScoreCard($id){
        $game = CricketGame::find($id);
       
        
        if($game){
             $this->matchInfo($game);
              $game = CricketGame::find($id);
            $score = ScoreCard::where('match_id',$id)->first();
            $data = [
                    't1' => $game->t1,
                    't1score' => $score->t1score,
                    't1wickets' => $score->t1wickets,
                    't1overs' => $score->t1overs,
                    't2' => $game->t2,
                    't2score' => $score->t2score,
                    't2wickets' => $score->t2wickets,
                    't2overs' => $score->t2overs,
                    'status' => $score->status
                ];
                return response()->json(['status'=>'Success','message'=>'score card','data'=>$data]);
        }else{
            return response()->json(['status'=>'fail','message'=>'Records Not Found!','data'=>[]]);
        }
    }
    
    
    public function checkLiveMatchStarted(){
        $ist = 'Asia/Kolkata';

        // Get today's date in IST
        $startOfTodayIST = Carbon::now($ist)->startOfDay();
        $endOfTodayIST = $startOfTodayIST->copy()->endOfDay();
        
        // Convert the start and end of today in IST to GMT
        $startOfTodayGMT = $startOfTodayIST->copy()->setTimezone('UTC');
        $endOfTodayGMT = $endOfTodayIST->copy()->setTimezone('UTC');
        
        // Get current time in IST and convert to GMT for comparison
        $currentISTTime = Carbon::now($ist);
        $currentGMTTime = $currentISTTime->copy()->setTimezone('UTC');
        
        // Retrieve and sort the games scheduled for today in IST
        $crickets = CricketGame::whereBetween('datetimeGMT', [$startOfTodayGMT, $endOfTodayGMT])
            ->with('match_list')
            ->orderBy('datetimeGMT', 'asc')
            ->get();
        
        // Check if the match has started or not and add that information
        $crickets->each(function ($game) use ($currentGMTTime) {
            $game->status = $currentGMTTime->greaterThanOrEqualTo($game->datetimeGMT);
        });
       
        if(count($crickets)>0){
            foreach($crickets as $mt){
               $this->matchInfo($mt);
            }
        }
    }
    
    
    public function storeFootballGames(){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiv2.allsportsapi.com/football/?met=Fixtures&APIkey=bfbea4a10dd90befbaf6f11b55694cc246a8acb8f2ec93536307310b06a5b99a&from=2024-06-01&to=2024-06-5',
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
        $games = json_decode($response);
        $matches = array();
        if(count($games->result)>0){
            foreach($games->result as $game){
                $mt['game_title'] = $game->event_home_team . ' Vs ' . $game->event_away_team;
                $mt['t1'] = $game->event_home_team;
                $mt['t2'] = $game->event_away_team;
                $mt['run_date_time'] =  Carbon::createFromFormat('Y-m-d H:i', $game->event_date . ' ' . $game->event_time);
                $mt['status'] = 0;
                $mt['event_key'] = $game->event_key;
                
                $matches[] = $mt;
            }
        }
        
        if(count($matches)>0){
                  foreach($matches as $match){
                      $mt = FootballGame::where('event_key',$match['event_key'])->first();
                      if(!$mt){
                         $mt = FootballGame::insert($match);
                         
                      }else{
                          $mt->update($match);
                      }
                      
                  }
              }
         
          return response()->json(['status'=>'Success','message'=>'Records Saved Successfully!','data'=>[]]);
        
        
        

    }
    
    
    public function apiGetFootballScore($id){
        $fg = FootballGame::find($id);
         if(!$fg){
            return response()->json(['status'=>'fail','message'=>'Records Not Found!','data'=>[]]);
         }
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apiv2.allsportsapi.com/football/?met=Livescore&APIkey=bfbea4a10dd90befbaf6f11b55694cc246a8acb8f2ec93536307310b06a5b99a',
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
         $games = json_decode($response);
          $data = [
                            'game_title' => $fg->game_title,
                            't1' => $fg->t1,
                            't2' => $fg->t2,
                            'results' => '0 - 0',
                            'status' => 'Match not Started Yet'
                         ];
         if(isset($games->result) && count($games->result)>0){
             foreach($games->result as $game){
                 if($game->event_key == $fg->event_key){
                     $fg->update(['status'=>1]);
                     $data1 = [
                            'game_title' => $fg->game_title,
                            't1' => $fg->t1,
                            't2' => $fg->t2,
                            'results' => $game->event_final_result,
                            'status' => 'Score Board'
                         ];
                         
                 }
             }
         }
         if(isset($data1)){
             $response = $data1;
         }else{
             $response = $data;
         }
         
          return response()->json(['status'=>'Success','message'=>'score card','data'=>$response]);
         
    }
    
    
    
     public function checkLiveMatchStartedfootball(){
        $ist = 'Asia/Kolkata';

        // Get today's date in IST
        $startOfTodayIST = Carbon::now($ist)->startOfDay();
        $endOfTodayIST = $startOfTodayIST->copy()->endOfDay();
        
        // Convert the start and end of today in IST to GMT
        $startOfTodayGMT = $startOfTodayIST->copy()->setTimezone('UTC');
        $endOfTodayGMT = $endOfTodayIST->copy()->setTimezone('UTC');
        
        // Get current time in IST and convert to GMT for comparison
        $currentISTTime = Carbon::now($ist);
        $currentGMTTime = $currentISTTime->copy()->setTimezone('UTC');
        
        // Retrieve and sort the games scheduled for today in IST
        $crickets = FootballGame::whereBetween('run_date_time', [$startOfTodayGMT, $endOfTodayGMT])
            ->with('match_list')
            ->orderBy('run_date_time', 'asc')
            ->get();
        
        // Check if the match has started or not and add that information
        $crickets->each(function ($game) use ($currentGMTTime) {
            $game->status = $currentGMTTime->greaterThanOrEqualTo($game->datetimeGMT);
        });
       
        if(count($crickets)>0){
            foreach($crickets as $mt){
               $this->apiGetFootballScore($mt->id);
            }
        }
        
        dd($crickets);
    }
    
    
}
