<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $url = 'https://api.cricapi.com/v1/series_info?apikey=37bdeed7-3e78-46b4-a0ed-fab98601e2b6&offset=0&id=e079ef23-b5e9-4802-93e9-dd2f27db0533';
    
    
    protected function getSeriesData(){
        $result = json_decode(file_get_contents("https://api.cricapi.com/v1/series?apikey=37bdeed7-3e78-46b4-a0ed-fab98601e2b6&offset=0"));
        
        return $result->data;
    }
    
    
    
    protected function getResults(){
        $result = json_decode(file_get_contents("https://api.cricapi.com/v1/series_info?apikey=37bdeed7-3e78-46b4-a0ed-fab98601e2b6&offset=0&id=e079ef23-b5e9-4802-93e9-dd2f27db0533"));
        $matches = array();
      if(isset($result->data->matchList)){
            $matchList = $result->data->matchList;
           
            foreach($matchList as $match){
               if(isset( $match->teamInfo)){
                    $mt['match_id'] = $match->id;
                    $teaminfo = $match->teamInfo;
                   $team1 = isset($teaminfo[0]->name) ? $teaminfo[0]->name : 'Tbc';
                                 $team2 = isset($teaminfo[1]->name) ? $teaminfo[1]->name : 'Tbc';
                                $mt['game_title'] = $team1. ' Vs '.$team2;
                                $t1 = isset($teaminfo[0]->shortname) ? $teaminfo[0]->shortname : 'Tbc';
                                $t2 = isset($teaminfo[1]->shortname) ? $teaminfo[1]->shortname : 'Tbc';
                                $mt['t1'] = $t1;
                                $mt['t2'] = $t2;
                    $mt['t1img'] = $teaminfo[0]->img ?? ''; 
                    $mt['t2img'] = $teaminfo[1]->img ?? '';
                    $mt['run_date_time'] = $match->dateTimeGMT;
                    $mt['datetimeGMT'] = $match->dateTimeGMT;
                   
                        $mt['status'] = 0;
                    
                   
                    $mt['series'] = 'e079ef23-b5e9-4802-93e9-dd2f27db0533';
                    
                    $matches[] = $mt;
               }
            }
            
    
   
       
      }
      
        return $matches;
    }
    
    protected function getMatchInfo_mainvoid($id){
        // $id = '47bdff44-5296-498c-aa85-d4241201f3f8';
        $url = 'https://api.cricapi.com/v1/match_info?apikey=37bdeed7-3e78-46b4-a0ed-fab98601e2b6&offset=0&id='.$id;
       
         $result = json_decode(file_get_contents($url));
         $mt['status'] = $result->data->status;
        //   dd($result);
         if(isset($result->data->score) && count($result->data->score)>0){
           
             $mt['t1score'] = $result->data->score[0]->r;
             $mt['t1overs'] = $result->data->score[0]->o;
             $mt['t1wickets'] = $result->data->score[0]->w;
             $mt['t2score'] = $result->data->score[1]->r ?? 0;
             $mt['t2overs'] = $result->data->score[1]->o ?? 0;
             $mt['t2wickets'] = $result->data->score[1]->w ?? 0;
         }
         
        
         return $mt;
        
    }
    
}
