<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="author" content="NobleUI">
  <meta name="keywords" content="">
  @include('layouts.header-url')

</head>
<style>
    .order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
}
</style>
<style>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-content {
  background-color: white;
  color: black;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  cursor: pointer;
}
.badge {
    display: inline-block;
    font-weight: bold;
    color: white;
    background-color: gray;
    border-radius: 12px;
}

.badge-success {
    background-color: green;
}
</style>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<body>


  <div class="main-wrapper Dashboard-bg customResponsive">
    <!-- partial:partials/_sidebar.html -->
    <div class="left-side-bar">@include('layouts.left-side-bar')</div>
    <div class="page-wrapper bg-none">
      <!-- partial:partials/_navbar.html -->
      <div class="top-header-section">@include('layouts.header')</div>
      <!-- partial -->
     
      
      <div class="page-content">
 
  <div id="notificationPopup" class="popup" style="display: none;">
  <div class="popup-content">
    <span id="closePopup" class="close">X</span>
    <p id="popupMessage">New data is available!</p>
  </div>
</div>


       
        <div class="row">
          <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                  <div class="row">
         <div class="col-md-4 col-xl-3">
            <a href="{{route('admin_website_dashboard_detail',['id'=>'newsilver.art'])}}">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">New Silver</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span id="total"></span></h2>
                        <p class="m-b-0">Cricket<span class="f-right" id="cricket"></span><span class="f-right badge badge-success" id="cricketnew"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="football"></span><span class="f-right badge badge-success" id="footballnew"></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tennis"></span><span class="f-right badge badge-success" id="tennisnew"></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racing"></span><span class="f-right badge badge-success" id="horse-racingnew"></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racing"></span><span class="f-right badge badge-success" id="greyhound-racingnew"></p>
                    </div>
                </div>
            </a>
        </div>
                    <div class="col-md-4 col-xl-3">
            <a href="{{route('admin_website_dashboard_detail',['id'=>'allpanel.art'])}}">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">All Panel</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span id="totalallpanel"></span></h2>
                        <p class="m-b-0">Cricket<span class="f-right" id="cricketallpanel"></span><span class="f-right badge badge-success" id="cricketnewallpanel"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="footballallpanel"></span><span class="f-right badge badge-success" id="footballnewallpanel"></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tennisallpanel"></span><span class="f-right badge badge-success" id="tennisnewallpanel"></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racingallpanel"></span><span class="f-right badge badge-success" id="horse-racingnewallpanel"></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racingallpanel"></span><span class="f-right badge badge-success" id="greyhound-racingnewallpanel"></p>
                    </div>
                </div>
            </a>
        </div>
                    <div class="col-md-4 col-xl-3">
            <a href="{{route('admin_website_dashboard_detail',['id'=>'crickekbuz.art'])}}">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Crickekbuz</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span id="totalcrickekbuz"></span></h2>
                        <p class="m-b-0">Cricket<span class="f-right" id="cricketcrickekbuz"></span><span class="f-right badge badge-success" id="cricketnewcrickekbuz"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="footballcrickekbuz"></span><span class="f-right badge badge-success" id="footballnewcrickekbuz"></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tenniscrickekbuz"></span><span class="f-right badge badge-success" id="tennisnewcrickekbuz"></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racingcrickekbuz"></span><span class="f-right badge badge-success" id="horse-racingnewcrickekbuz"></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racingcrickekbuz"></span><span class="f-right badge badge-success" id="greyhound-racingnewcrickekbuz"></p>
                    </div>
                </div>
            </a>
        </div>
    
                
               <div class="col-md-4 col-xl-3">
            <a href="{{route('admin_website_dashboard_detail',['id'=>'laserclub.art'])}}">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Laser</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span id="totalLaser"></span></h2>
                        <p class="m-b-0">Cricket<span class="f-right" id="cricketLaser"></span><span class="f-right badge badge-success" id="cricketnewLaser"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="footballLaser"></span><span class="f-right badge badge-success" id="footballnewLaser"></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tennisLaser"></span><span class="f-right badge badge-success" id="tennisnewLaser"></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racingLaser"></span><span class="f-right badge badge-success" id="horse-racingnewLaser"></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racingLaser"></span><span class="f-right badge badge-success" id="greyhound-racingnewLaser"></p>
                    </div>
                </div>
            </a>
        </div>
             
                    
               <div class="col-md-4 col-xl-3">
            <a href="{{route('admin_website_dashboard_detail',['id'=>'gold365.art'])}}">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Gold365</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span id="totalgold"></span></h2>
                        <p class="m-b-0">Cricket<span class="f-right" id="cricketgold"></span><span class="f-right badge badge-success" id="cricketnewgold"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="footballgold"></span><span class="f-right badge badge-success" id="footballnewgold"></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tennisgold"></span><span class="f-right badge badge-success" id="tennisnewgold"></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racinggold"></span><span class="f-right badge badge-success" id="horse-racingnewgold"></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racinggold"></span><span class="f-right badge badge-success" id="greyhound-racingnewgold"></p>
                    </div>
                </div>
            </a>
        </div>
                
         
                </div>
              </div>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>



    

  @include('layouts.footer')
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    var previousData = {
        total: 0,
        CricketPlaceBetcount: 0,
        FootballPlaceBetcount: 0,
        TennisPlaceBetcount: 0,
        HorseRacingPlaceBetcount: 0,
        GreyhoundRacingPlaceBetcount: 0
    };

    var isFirstLoad = true;

    function fetchDashboardData() {
        $.ajax({
            url: 'https://ujala11games.com/admin/admin-website-dashboard',
            method: 'GET',
            success: function(data) {
                console.log(data);
                var newData = data.newsilver.response;
                var message = "";

                if (!isFirstLoad) {
                    // if (newData.total > previousData.total) {
                    //     message += "Total count increased. ";
                    // }
                    if (newData.CricketPlaceBetcount > previousData.CricketPlaceBetcount) {
                        message += "New Cricket Bet Is Placed.";
                    }
                    if (newData.FootballPlaceBetcount > previousData.FootballPlaceBetcount) {
                        message += "New Football Bet Is Placed.";
                    }
                    if (newData.TennisPlaceBetcount > previousData.TennisPlaceBetcount) {
                        message += "New Tennis Bet Is Placed.";
                    }
                    if (newData.HorseRacingPlaceBetcount > previousData.HorseRacingPlaceBetcount) {
                        message += "New Horse Racing Bet Is Placed.";
                    }
                    if (newData.GreyhoundRacingPlaceBetcount > previousData.GreyhoundRacingPlaceBetcount) {
                        message += "New Greyhound Bet Is Placed.";
                    }

                    // Show popup if there's any increment
                    if (message !== "") {
                        $('#popupMessage').text(message);
                        $('#notificationPopup').show();
                    }
                }

                // Update previous data with new data
                previousData = {
                    total: newData.total ?? 0,
                    CricketPlaceBetcount: newData.CricketPlaceBetcount ?? 0,
                    CricketPlaceBetcountnew: newData.CricketPlaceBetcountnew ?? 0,
                    FootballPlaceBetcount: newData.FootballPlaceBetcount ?? 0,
                    FootballPlaceBetcountnew: newData.FootballPlaceBetcountnew ?? 0,
                    TennisPlaceBetcount: newData.TennisPlaceBetcount ?? 0,
                    TennisPlaceBetcountnew: newData.TennisPlaceBetcountnew ?? 0,
                    HorseRacingPlaceBetcount: newData.HorseRacingPlaceBetcount ?? 0,
                    HorseRacingPlaceBetcountnew: newData.HorseRacingPlaceBetcountnew ?? 0,
                    GreyhoundRacingPlaceBetcount: newData.GreyhoundRacingPlaceBetcount ?? 0,
                    GreyhoundRacingPlaceBetcountnew: newData.GreyhoundRacingPlaceBetcountnew ?? 0
                };

                // Update the displayed counts
                $('#total').text(newData.total ?? "");
                $('#cricket').text(newData.CricketPlaceBetcount ?? "");
                $('#cricketnew').text(newData.CricketPlaceBetcountnew ?? "");
                $('#football').text(newData.FootballPlaceBetcount ?? "");
                $('#footballnew').text(newData.FootballPlaceBetcountnew ?? "");
                $('#tennis').text(newData.TennisPlaceBetcount ?? "");
                $('#tennisnew').text(newData.TennisPlaceBetcountnew ?? "");
                $('#horse-racing').text(newData.HorseRacingPlaceBetcount ?? "");
                $('#horse-racingnew').text(newData.HorseRacingPlaceBetcountnew ?? "");
                $('#greyhound-racing').text(newData.GreyhoundRacingPlaceBetcount ?? "");
                $('#greyhound-racingnew').text(newData.GreyhoundRacingPlaceBetcountnew ?? "");

                // Set the flag to false after the first load
                isFirstLoad = false;
                 
                   // Update the displayed counts  All Panel
                $('#totalallpanel').text(data.allpanel.response.total ?? "");
                $('#cricketallpanel').text(data.allpanel.response.CricketPlaceBetcount ?? "");
                $('#cricketnewallpanel').text(data.allpanel.response.CricketPlaceBetcountnew ?? "");
                $('#footballallpanel').text(data.allpanel.response.FootballPlaceBetcount ?? "");
                $('#footballnewallpanel').text(data.allpanel.response.FootballPlaceBetcountnew ?? "");
                $('#tennisallpanel').text(data.allpanel.response.TennisPlaceBetcount ?? "");
                $('#tennisnewallpanel').text(data.allpanel.response.TennisPlaceBetcountnew ?? "");
                $('#horse-racingallpanel').text(data.allpanel.response.HorseRacingPlaceBetcount ?? "");
                $('#horse-racingnewallpanel').text(data.allpanel.response.HorseRacingPlaceBetcountnew ?? "");
                $('#greyhound-racingallpanel').text(data.allpanel.response.GreyhoundRacingPlaceBetcount ?? "");
                $('#greyhound-racingnewallpanel').text(data.allpanel.response.GreyhoundRacingPlaceBetcountnew ?? "");
                
                   // Update the displayed counts  CRCIKET BZZZZ
                $('#totalcrickekbuz').text(data.crickekbuz.response.total ?? "");
                $('#cricketcrickekbuz').text(data.crickekbuz.response.CricketPlaceBetcount ?? "");
                $('#cricketnewcrickekbuz').text(data.crickekbuz.response.CricketPlaceBetcountnew ?? "");
                // $('#cricketcrickekbuz').text(data.crickekbuz.response.CricketPlaceBetcount ?? "");
                // $('#cricketnewcrickekbuz).text(data.crickekbuz.response.CricketPlaceBetcountnew ?? "");
                $('#footballcrickekbuz').text(data.crickekbuz.response.FootballPlaceBetcount ?? "");
                $('#footballnewcrickekbuz').text(data.crickekbuz.response.FootballPlaceBetcountnew ?? "");
                $('#tenniscrickekbuz').text(data.crickekbuz.response.TennisPlaceBetcount ?? "");
                $('#tennisnewcrickekbuz').text(data.crickekbuz.response.TennisPlaceBetcountnew ?? "");
                $('#horse-racingcrickekbuz').text(data.crickekbuz.response.HorseRacingPlaceBetcount ?? "");
                $('#horse-racingnewcrickekbuz').text(data.crickekbuz.response.HorseRacingPlaceBetcountnew ?? "");
                $('#greyhound-racingcrickekbuz').text(data.crickekbuz.response.GreyhoundRacingPlaceBetcount ?? "");
                $('#greyhound-racingnewcrickekbuz').text(data.crickekbuz.response.GreyhoundRacingPlaceBetcountnew ?? "");
                
                 // Update the displayed counts  Laser
                $('#totalLaser').text(data.Laser.response.total ?? "");
                $('#cricketLaser').text(data.Laser.response.CricketPlaceBetcount ?? "");
                $('#cricketnewLaser').text(data.Laser.response.CricketPlaceBetcountnew ?? "");
                $('#footballLaser').text(data.Laser.response.FootballPlaceBetcount ?? "");
                $('#footballnewLaser').text(data.Laser.response.FootballPlaceBetcountnew ?? "");
                $('#tennisLaser').text(data.Laser.response.TennisPlaceBetcount ?? "");
                $('#tennisnewLaser').text(data.Laser.response.TennisPlaceBetcountnew ?? "");
                $('#horse-racingLaser').text(data.Laser.response.HorseRacingPlaceBetcount ?? "");
                $('#horse-racingnewLaser').text(data.Laser.response.HorseRacingPlaceBetcountnew ?? "");
                $('#greyhound-racingLaser').text(data.Laser.response.GreyhoundRacingPlaceBetcount ?? "");
                $('#greyhound-racingnewLaser').text(data.Laser.response.GreyhoundRacingPlaceBetcountnew ?? "");
                
                 // Update the displayed counts  gold
                $('#totalgold').text(data.gold.response.total ?? "");
                $('#cricketgold').text(data.gold.response.CricketPlaceBetcount ?? "");
                $('#cricketnewgold').text(data.gold.response.CricketPlaceBetcountnew ?? "");
                $('#footballgold').text(data.gold.response.FootballPlaceBetcount ?? "");
                $('#footballnewgold').text(data.gold.response.FootballPlaceBetcountnew ?? "");
                $('#tennisgold').text(data.gold.response.TennisPlaceBetcount ?? "");
                $('#tennisnewgold').text(data.gold.response.TennisPlaceBetcountnew ?? "");
                $('#horse-racinggold').text(data.gold.response.HorseRacingPlaceBetcount ?? "");
                $('#horse-racingnewgold').text(data.gold.response.HorseRacingPlaceBetcountnew ?? "");
                $('#greyhound-racinggold').text(data.gold.response.GreyhoundRacingPlaceBetcount ?? "");
                $('#greyhound-racingnewgold').text(data.gold.response.GreyhoundRacingPlaceBetcountnew ?? "");
            }
        });
    }

    $(document).ready(function() {
        fetchDashboardData();
        setInterval(fetchDashboardData, 5000); // Fetch data every 5 seconds

        // Close popup when close button is clicked
        // $('#closePopup').click(function() {
        //     $('#notificationPopup').hide();
        // });
            // Close popup when clicking anywhere on the page
        $(document).click(function(event) {
            if (!$(event.target).closest('.popup-content').length) {
                $('#notificationPopup').hide();
            }
        });
    });
</script>


