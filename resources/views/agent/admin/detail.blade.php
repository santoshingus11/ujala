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
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">Cricket Match Bet List</h4>
                     <h4 class=" mb-0 align-right">Total : {{$response['response']['CricketPlaceBetcount'] ?? ""}}</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                              <th class="text-center">Bet ID</th>
                                <th class="text-center">Event Name</th>
                                <th class="text-center">Event Type</th>
                                <th class="text-center">Back/Lay</th>
                                <th class="text-center">Bet stake</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']['CricketPlaceBet']))
                             @foreach($response['response']['CricketPlaceBet'] as $CricketPlaceBetval)
                            <tr class="back">
                                <td class="text-center">{{$CricketPlaceBetval['id']}}</td>
                                <td class="text-center">{{$CricketPlaceBetval['team_name']}}</td>
                                <td class="text-center">Cricket</td>
                                <td class="text-center">{{$CricketPlaceBetval['back_lay']}}</td>
                                <td class="text-center">{{$CricketPlaceBetval['bet_stake']}}</td>
                               
                                
                                <td class="text-center">{{$CricketPlaceBetval['created_at']}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin_website_dashboard_detail_reject',['id'=>$CricketPlaceBetval['id'],'website'=>$website,'game'=>'Cricket'])}}" class="btn btn-danger">Reject</a>
                                      @if(!empty($CricketPlaceBetval['status']) == 0)
                                    <a href="{{route('admin_website_dashboard_detail_accept',['id'=>$CricketPlaceBetval['id'],'website'=>$website,'game'=>'Cricket'])}}" class="btn btn-success">Accept</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>

<div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">Football Match Bet List</h4>
                     <h4 class=" mb-0 align-right">Total : {{$response['response']['FootballPlaceBetcount'] ?? ""}}</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                              <th class="text-center">Bet ID</th>
                                <th class="text-center">Event Name</th>
                                <th class="text-center">Event Type</th>
                                <th class="text-center">Back/Lay</th>
                                <th class="text-center">Bet stake</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']['FootballPlaceBet']))
                             @foreach($response['response']['FootballPlaceBet'] as $FootballPlaceBetval)
                            <tr class="back">
                                <td class="text-center">{{$FootballPlaceBetval['id']}}</td>
                                <td class="text-center">{{$FootballPlaceBetval['team_name']}}</td>
                                <td class="text-center">FootBall</td>
                                <td class="text-center">{{$FootballPlaceBetval['back_lay']}}</td>
                                <td class="text-center">{{$FootballPlaceBetval['bet_stake']}}</td>
                               
                                
                                <td class="text-center">{{$FootballPlaceBetval['created_at']}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin_website_dashboard_detail_reject',['id'=>$FootballPlaceBetval['id'],'website'=>$website,'game'=>'FootBall'])}}" class="btn btn-danger">Reject</a>
                                     @if(!empty($FootballPlaceBetval['status']) == 0)
                                    <a href="{{route('admin_website_dashboard_detail_accept',['id'=>$FootballPlaceBetval['id'],'website'=>$website,'game'=>'FootBall'])}}" class="btn btn-success">Accept</a>
                                     @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>


<div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">Tennis Match Bet List</h4>
                     <h4 class=" mb-0 align-right">Total : {{$response['response']['TennisPlaceBetcount'] ?? ""}}</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                              <th class="text-center">Bet ID</th>
                                <th class="text-center">Event Name</th>
                                <th class="text-center">Event Type</th>
                                <th class="text-center">Back/Lay</th>
                                <th class="text-center">Bet stake</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']['TennisPlaceBet']))
                             @foreach($response['response']['TennisPlaceBet'] as $TennisPlaceBetval)
                            <tr class="back">
                                <td class="text-center">{{$TennisPlaceBetval['id']}}</td>
                                <td class="text-center">{{$TennisPlaceBetval['team_name']}}</td>
                                <td class="text-center">Tennis</td>
                                <td class="text-center">{{$TennisPlaceBetval['back_lay']}}</td>
                                <td class="text-center">{{$TennisPlaceBetval['bet_stake']}}</td>
                               
                                
                                <td class="text-center">{{$TennisPlaceBetval['created_at']}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin_website_dashboard_detail_reject',['id'=>$TennisPlaceBetval['id'],'website'=>$website,'game'=>'Tennis'])}}" class="btn btn-danger">Reject</a>
                                    
                                     @if(!empty($TennisPlaceBetval['status']) == 0)
                                    <a href="{{route('admin_website_dashboard_detail_accept',['id'=>$TennisPlaceBetval['id'],'website'=>$website,'game'=>'Tennis'])}}" class="btn btn-success">Accept</a>
                                     @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>

<div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">HorseRacing Match Bet List</h4>
                     <h4 class=" mb-0 align-right">Total : {{$response['response']['HorseRacingPlaceBetcount'] ?? ""}}</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                              <th class="text-center">Bet ID</th>
                                <th class="text-center">Event Name</th>
                                <th class="text-center">Event Type</th>
                                <th class="text-center">Back/Lay</th>
                                <th class="text-center">Bet stake</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']['HorseRacingPlaceBet']))
                             @foreach($response['response']['HorseRacingPlaceBet'] as $HorseRacingPlaceBetval)
                            <tr class="back">
                                <td class="text-center">{{$HorseRacingPlaceBetval['id']}}</td>
                                <td class="text-center">{{$HorseRacingPlaceBetval['team_name']}}</td>
                                <td class="text-center">HorseRacing</td>
                                <td class="text-center">{{$HorseRacingPlaceBetval['back_lay']}}</td>
                                <td class="text-center">{{$HorseRacingPlaceBetval['bet_stake']}}</td>
                               
                                
                                <td class="text-center">{{$HorseRacingPlaceBetval['created_at']}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin_website_dashboard_detail_reject',['id'=>$HorseRacingPlaceBetval['id'],'website'=>$website,'game'=>'HorseRacing'])}}" class="btn btn-danger">Reject</a>
                                       @if(!empty($HorseRacingPlaceBetval['status']) == 0)
                                    <a href="{{route('admin_website_dashboard_detail_accept',['id'=>$HorseRacingPlaceBetval['id'],'website'=>$website,'game'=>'HorseRacing'])}}" class="btn btn-success">Accept</a>
                                     @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>

<div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">GreyhoundRacing Match Bet List</h4>
                     <h4 class=" mb-0 align-right">Total : {{$response['response']['GreyhoundRacingPlaceBetcount'] ?? ""}}</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                              <th class="text-center">Bet ID</th>
                                <th class="text-center">Event Name</th>
                                <th class="text-center">Event Type</th>
                                <th class="text-center">Back/Lay</th>
                                <th class="text-center">Bet stake</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']['GreyhoundRacingPlaceBet']))
                             @foreach($response['response']['GreyhoundRacingPlaceBet'] as $GreyhoundRacingPlaceBetval)
                            <tr class="back">
                                <td class="text-center">{{$GreyhoundRacingPlaceBetval['id']}}</td>
                                <td class="text-center">{{$GreyhoundRacingPlaceBetval['team_name']}}</td>
                                <td class="text-center">GreyhoundRacing</td>
                                <td class="text-center">{{$GreyhoundRacingPlaceBetval['back_lay']}}</td>
                                <td class="text-center">{{$GreyhoundRacingPlaceBetval['bet_stake']}}</td>
                               
                                
                                <td class="text-center">{{$GreyhoundRacingPlaceBetval['created_at']}}</td>
                                <td class="text-center">
                                    <a href="{{route('admin_website_dashboard_detail_reject',['id'=>$GreyhoundRacingPlaceBetval['id'],'website'=>$website,'game'=>'GreyhoundRacing'])}}" class="btn btn-danger">Reject</a>
                                    @if(!empty($GreyhoundRacingPlaceBetval['status']) == 0)
                                    <a href="{{route('admin_website_dashboard_detail_accept',['id'=>$GreyhoundRacingPlaceBetval['id'],'website'=>$website,'game'=>'GreyhoundRacing'])}}" class="btn btn-success">Accept</a>
                                     @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>

      </div>
    </div>
  </div>



    

  @include('layouts.footer')