<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GreyhoundRacingMatch;
use App\Models\GreyhoundRacingTimeSlot;

class GreyhoundRacingGame extends Model
{
    use HasFactory;
    protected $table = 'greyhound_racing_games';
    protected $guarded = [];
    public function time_slots(){
        return $this->hasMany(GreyhoundRacingTimeSlot::class,'game_id','id');
    }
}
