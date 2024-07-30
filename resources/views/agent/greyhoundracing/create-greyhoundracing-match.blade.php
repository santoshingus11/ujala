<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="keywords" content="">
  @include('layouts.header-url')
  <script>
    // Wait for the DOM to be ready
    document.addEventListener("DOMContentLoaded", function() {
      var startDateInput = document.getElementById("start-date");
      var endDateInput = document.getElementById("end-date");

      // Get today's date in the format "YYYY-MM-DD"
      var today = new Date().toISOString().split('T')[0];

      // Set initial values for date inputs
      startDateInput.value = today;
      endDateInput.value = today;

      // Initialize date picker for start date
      flatpickr(startDateInput, {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
          endDatePicker.set("minDate", dateStr);
        }
      });

      // Initialize date picker for end date
      var endDatePicker = flatpickr(endDateInput, {
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
          startDatePicker.set("maxDate", dateStr);
        }
      });
    });
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        @include('layouts.top-balance-section')

        <div class="row">
          <div class="col-12 col-xl-12 grid-margin stretch-card">
            <div class="card overflow-hidden">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                    @if(Session::has('message'))
                        <div class="alert alert-success mt-3">
                            <p>{{Session::get('message')}}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                 <h4 class=" mb-0">Create Grey Hound Racing Match</h4>
                  <div class="d-flex align-items-center flex-wrap text-nowrap">
                  <input type="hidden" name="type" id="type" value="p_l">
                  <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">

                    {{-- <button type="button" onclick="downloadaccountstatement('admin/account-statement-download')" class="btn btn-primary Refresh btn-icon-text mb-2 mb-md-0">
                      Download CSV
                    </button> --}}

                  </div>
                </div>

                    <form action="{{route('admin-greyhoundracing-match-submit')}}" id="create_match_form" enctype="multipart/form-data" method="POST">
                        @csrf
                        {{method_field('POST')}}
                        <div class="row mb-4">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mybets-date-picker">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="" class="col-12 col-form-label" style="font-weight: bolder;">Team Name*</label>
                                        <input name="team_name" id="team_name" type="text" autocomplete="off" value="{{old('team_name')}}" placeholder="Team Name" class="mx-input w-100" autofocus>
                                    </div>
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <label for="" class="col-12 col-form-label" style="font-weight: bolder;">Back*</label>
                                        <input name="back_value" id="back_value" type="number" min="0" step=".0000000001" autocomplete="off" value="{{old('back_value')}}" placeholder="Back value" class="mx-input w-100">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="col-12 col-form-label" style="font-weight: bolder;">Lay*</label>
                                        <input name="lay_value" id="lay_value" type="number" min="0" step=".0000000001" autocomplete="off" value="{{old('lay_value')}}" placeholder="Lay value" class="mx-input w-100">
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <h6 class="mb-2">Match Type*</h6>
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="match_type" id="match_type_1" value="match_odds" checked>
                                          <label class="form-check-label" for="match_type_1">
                                            Match Odds
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 text-left">
                                        <label for="toDate" class="col-12 col-form-label w-100 height-24"></label>
                                        <button class="btn btn-primary" style="height: 35px;">SUBMIT</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$time_slot_id}}" name="time_slot_id">
                        <input type="hidden" name="match_id" id="match_id">
                    </form>

                  </div>
                </div>
              </div>
            </div>
             {{-- end row --}}
             
            <div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">Grey Hound Racing Match List</h4>
                      <div class="d-flex align-items-center flex-wrap text-nowrap">
                      <input type="hidden" name="type" id="type" value="p_l">
                      <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">
    
                        {{-- <button type="button" onclick="downloadaccountstatement('admin/account-statement-download')" class="btn btn-primary Refresh btn-icon-text mb-2 mb-md-0">
                          Download CSV
                        </button> --}}
    
                      </div>
                    </div>
    
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Action</th>
                                 <th scope="col">Bet Histories</th>
                                <th scope="col">Team Name</th>
                                <th scope="col">Back</th>
                                <th scope="col">Lay</th>
                                <th scope="col">Status</th>
                                <th scope="col">Back Status</th>
                                <th scope="col">Lay Status</th>
                                <th scope="col">Win_Loss</th>
                                <th scope="col">Match Type</th>
                                <th scope="col">Created At</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($greyhoundracings as $d)
                                <tr>
                                    <th scope="row">{{$loop->index+1}}</th>
                                    <td><button class="badge badge-primary edit_btn" style="background-color:rgb(68, 97, 242);" data-match-id={{$d->id}} data-team-name='{{$d->team_name}}' data-back-value={{$d->back_value}} data-lay-value={{$d->lay_value}} data-match-type={{$d->match_type}} >Edit</button>
                                        <a href="{{route('delete.greyhoundracing.match',$d->id)}}" class="badge badge-danger">Delete</a>
                                    </td>
                                      <td> 
                                       <a href="{{route('show.cricket.match',['id' => $d->id, 'domain' => 'newsilver.art', 'game' => 'greyhound'])}}" class="badge badge-eye"> <img src="https://newsilver.art/public/assets/logologin.png" alt="Profile Picture 1"></a>
                                       <a href="{{route('show.cricket.match',['id' => $d->id, 'domain' => 'allpanel.art', 'game' => 'greyhound'])}}" class="badge badge-eye"> <img src="https://allpanel.art/public/login/logo.png" alt="Profile Picture 1"></a>
                                       <a href="{{route('show.cricket.match',['id' => $d->id, 'domain' => 'crickekbuz.art', 'game' => 'greyhound'])}}" class="badge badge-eye"> <img src="https://crickekbuz.art/public/login/logo.png" alt="Profile Picture 1"></a>
                                       <a href="{{route('show.cricket.match',['id' => $d->id, 'domain' => 'laserclub.art', 'game' => 'greyhound'])}}" class="badge badge-eye"> <img src="https://laserclub.art/public/assets/logo.gif" alt="Profile Picture 1"></a>
                                       <a href="{{route('show.cricket.match',['id' => $d->id, 'domain' => 'gold365.art', 'game' => 'greyhound'])}}" class="badge badge-eye"> <img src="https://gold365.art/public/frontend/assets/images/logo-login.png" alt="Profile Picture 1"></a>
                                    </td>
                                    <td><a href="/admin/greyhoundracing/change-match-status/{{$d->id}}/{{$d->time_slot_id}}" style=@if(isset($d->win_loss))  {{"color:red;"}} @else {{"color:blue;"}} @endif</a>{{ $d->team_name }}</a></td>
                                    <td>{{ $d->back_value }}</td>
                                    <td>{{ $d->lay_value }}</td>
                                    <td>{{ $d->status==0? 'Inactive' : 'Active' }}</td>
                                    @if($d->back_status===0)
                                    <td><a href="{{route('greyhoundracing.activate.back_status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(68, 97, 242);">Activate</a></td>
                                    @else
                                        <td><a href="{{route('greyhoundracing.deactivate.back_status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(240, 36, 36);">Deactivate</a></td>
                                    @endif
                                    
                                    @if($d->lay_status===0)
                                    <td><a href="{{route('greyhoundracing.activate.lay_status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(68, 97, 242);">Activate</a></td>
                                    @else
                                        <td><a href="{{route('greyhoundracing.deactivate.lay_status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(240, 36, 36);">Deactivate</a></td>
                                    @endif
                                    
                                    <td>{{ $d->win_loss }}</td>
                                    <td>{{ $d->match_type }}</td>
                                    <td>{{$d->created_at}}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
    
                    <div class="border-bottom mt-3"></div>
    
                  </div>
                </div>
              </div>
            </div>
            {{-- end row --}}
            
            
          </div>

      </div>
     
    </div>
  </div>

<script>
    $(function() {
        // validation error msg
        $('.alert').delay(3000).fadeOut('slow');

    });
</script>

@include('layouts.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  $( function() {
    $('#bonus_expired_date').datepicker({
        format: 'yyyy-mm-dd'
    }).datepicker("setDate",'now');
  } );
</script>
<script>
  $( function() {
    $(".edit_btn").click(function() {
console.log($(this).data("team-name"));
      $('#match_id').val($(this).data("match-id"));
      $('#team_name').val($(this).data("team-name"));
      $('#back_value').val($(this).data("back-value"));
      $('#lay_value').val($(this).data("lay-value"));
      $("input[name=match_type][value=" + $(this).data("match-type") + "]").prop('checked', true);
      
      $('#create_match_form').attr('action', "/admin/greyhoundracing/match-update")

    });
  } );
</script>

