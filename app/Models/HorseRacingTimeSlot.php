<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HorseRacingMatch;
use App\Models\HorseRacingGame;

class HorseRacingTimeSlot extends Model
{
    use HasFactory;
    protected $table = 'horse_racing_time_slots';
    
    public function match_list(){
        return $this->hasMany(HorseRacingMatch::class,'time_slot_id','id');
    }
    public function game(){
        return $this->belongsTo(HorseRacingGame::class,'game_id','id');
    }
    
}
