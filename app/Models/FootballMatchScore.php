<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatchScore extends Model
{
    use HasFactory;
    protected $table = 'football_matches_score';
    
    protected $guarded = [];  
}
