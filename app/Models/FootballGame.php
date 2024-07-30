<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FootballMatch;

class FootballGame extends Model
{
    use HasFactory;
    protected $table = 'football_games';
    protected $guarded = [];
    
    public function match_list(){
        return $this->hasMany(FootballMatch::class,'game_id','id');
    }
}
