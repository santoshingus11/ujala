<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TennisMatch;

class TennisGame extends Model
{
    use HasFactory;
    protected $table = 'tennis_games';
    protected $guarded = [];
    public function match_list(){
        return $this->hasMany(TennisMatch::class,'game_id','id');
    }
}
