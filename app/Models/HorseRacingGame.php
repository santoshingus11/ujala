<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HorseRacingMatch;

class HorseRacingGame extends Model
{
    use HasFactory;
    protected $table = 'horse_racing_games';
    protected $guarded = [];
    
    public function time_slots(){
        return $this->hasMany(HorseRacingTimeSlot::class,'game_id','id');
    }
}
