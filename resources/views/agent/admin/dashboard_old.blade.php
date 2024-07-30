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
                        <p class="m-b-0">Cricket<span class="f-right" id="cricket"></span></p>
                        <p class="m-b-0">Football<span class="f-right" id="football"></span></p>
                        <p class="m-b-0">Tennis<span class="f-right" id="tennis"></span></p>
                        <p class="m-b-0">Horse Racing<span class="f-right" id="horse-racing"></span></p>
                        <p class="m-b-0">Greyhound Racing<span class="f-right" id="greyhound-racing"></span></p>
                    </div>
                </div>
            </a>
        </div>
                
                    <div class="col-md-4 col-xl-3">
                     <a href="#">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Gold 360</h6>
                            <h2 class="text-right"><i class="fa fa-rocket f-left"></i></i><span>0</span></h2>
                            <p class="m-b-0">Cricket<span class="f-right">0</span></p>
                            <p class="m-b-0">Football<span class="f-right">0</span></p>
                            <p class="m-b-0">Tennis<span class="f-right">0</span></p>
                            <p class="m-b-0">Hourse Racing<span class="f-right">0</span></p>
                            <p class="m-b-0">Greyhound Racing<span class="f-right">0</span></p>
                        </div>
                    </div>
                     </a>
                </div>
                
                    <div class="col-md-4 col-xl-3">
                     <a href="#">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Cricketbzz</h6>
                            <h2 class="text-right"><i class="fa fa-rocket f-left"></i></i><span>0</span></h2>
                            <p class="m-b-0">Cricket<span class="f-right">0</span></p>
                            <p class="m-b-0">Football<span class="f-right">0</span></p>
                            <p class="m-b-0">Tennis<span class="f-right">0</span></p>
                            <p class="m-b-0">Hourse Racing<span class="f-right">0</span></p>
                            <p class="m-b-0">Greyhound Racing<span class="f-right">0</span></p>
                        </div>
                    </div>
                     </a>
                </div>
                
                    <div class="col-md-4 col-xl-3">
                     <a href="#">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">All panell</h6>
                            <h2 class="text-right"><i class="fa fa-rocket f-left"></i></i><span>0</span></h2>
                            <p class="m-b-0">Cricket<span class="f-right">0</span></p>
                            <p class="m-b-0">Football<span class="f-right">0</span></p>
                            <p class="m-b-0">Tennis<span class="f-right">0</span></p>
                            <p class="m-b-0">Hourse Racing<span class="f-right">0</span></p>
                            <p class="m-b-0">Greyhound Racing<span class="f-right">0</span></p>
                        </div>
                    </div>
                     </a>
                </div>
                
                    <div class="col-md-4 col-xl-3">
                     <a href="#">
                    <div class="card bg-c-blue order-card">
                        <div class="card-block">
                            <h6 class="m-b-20">Laser</h6>
                            <h2 class="text-right"><i class="fa fa-rocket f-left"></i></i><span>0</span></h2>
                            <p class="m-b-0">Cricket<span class="f-right">0</span></p>
                            <p class="m-b-0">Football<span class="f-right">0</span></p>
                            <p class="m-b-0">Tennis<span class="f-right">0</span></p>
                            <p class="m-b-0">Hourse Racing<span class="f-right">0</span></p>
                            <p class="m-b-0">Greyhound Racing<span class="f-right">0</span></p>
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
    function fetchDashboardData() {
        $.ajax({
            url: 'https://ujala11games.com/admin/admin-website-dashboard',
            method: 'GET',
            success: function(data) {
                console.log(data);
                $('#total').text(data.newsilver.response.total ?? "");
                $('#cricket').text(data.newsilver.response.CricketPlaceBetcount ?? "");
                $('#football').text(data.newsilver.response.FootballPlaceBetcount ?? "");
                $('#tennis').text(data.newsilver.response.TennisPlaceBetcount ?? "");
                $('#horse-racing').text(data.newsilver.response.HorseRacingPlaceBetcount ?? "");
                $('#greyhound-racing').text(data.newsilver.response.GreyhoundRacingPlaceBetcount ?? "");
            }
        });
    }

    $(document).ready(function() {
        fetchDashboardData();
        setInterval(fetchDashboardData, 5000); // Fetch data every 5 seconds
    });
</script>