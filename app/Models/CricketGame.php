<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CricketMatch;

class CricketGame extends Model
{
    use HasFactory;
    protected $table = 'cricket_games';
    protected $guarded = [];
    public function match_list(){
        return $this->hasMany(CricketMatch::class,'game_id','id');
    }
}
