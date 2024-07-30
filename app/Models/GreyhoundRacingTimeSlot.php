<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GreyhoundRacingMatch;
use App\Models\GreyhoundRacingGame;

class GreyhoundRacingTimeSlot extends Model
{
    use HasFactory;
    protected $table = 'greyhound_racing_time_slots';
    
    public function match_list(){
        return $this->hasMany(GreyhoundRacingMatch::class,'time_slot_id','id');
    }
    public function game(){
        return $this->belongsTo(GreyhoundRacingGame::class,'game_id','id');
    }
    
}
