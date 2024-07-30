<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    public function index(){
        return view('client.home');
    }
    public function home(){
        return view('client.home');
    }
    public function changepassword(){
        return view('client.changepassword');
    }
    public function transferstatement(){
        return view('client.transferstatement');
    }
    public function football_details(){
        return view('client.Football-Details');
    }
    public function mybets(){
        return view('client.mybets');
    }
    public function loss_profit(){
        return view('client.profitloss');
    }
    public function secureauth(){
        return view('client.secureauth');
    }
    public function message(){
        return view('client.message');
    }
    public function statement(){
        return view('client.accountstatement');
    }
    public function foot_ball(){
        return view('client.football');
    }
    public function ten_nis(){
        return view('client.Tennis');
    }
    public function cric_ket(){
        return view('client.cricket');
    }
    public function tenis(){
        return view('client.table-tennis');
    }
    public function dart(){
        return view('client.darts');
    }
    public function badmint_on(){
        return view('client.badminton');
    }
    public function kaba_ddi(){
        return view('client.Kabaddi');
    }
    public function queen_result(){
        return view('client.queen-results');
    }
    public function boxi_ng(){
        return view('client.boxing');
    }
    public function artss(){
        return view('client.arts');
    }
    public function motor_sport(){
        return view('client.Motor-Sport');
    }
    public function basketball(){
        return view('client.basketball');
    }
    public function election(){
        return view('client.election2023');
    }
    public function icc(){
        return view('client.Icc2023');
    }
    public function lottery(){
        return view('client.lottery');
    }
    public function casino(){
        return view('client.live-casino');
    }
     public function details4(){
        return view('client.Cricket-details-pages-4');
    }
     public function details3(){
        return view('client.Cricket-details-pages-3');
    }
    public function details2(){
        return view('client.Cricket-details-pages-2');
    }
    public function details(){
        return view('client.Cricket-details');
    }
    public function tennis_details(){
        return view('client.Tennis-details');
    }
    public function basket_details(){
        return view('client.basketball-details');
    }
    public function kabaddi_details(){
        return view('client.Kabaddi-details');
    }
    public function race20(){
        return view('client.race20');
    }
    public function queen_20(){
        return view('client.queen');
    }
    public function andarbahar2(){
        return view('client.andarbahar2');
    }
    public function dragon_tiger(){
        return view('client.20-20-dragon-tiger');
    }
    public function casino_result(){
        return view('client.casino-results');
    }
    public function andarbahar_result(){
        return view('client.andarbahar-results');
    }
    public function tiger_result(){
        return view('client.tiger-results');
    }
}
