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
                    @if (Session::has('message'))
                        <div class="alert alert-success mt-3">
                            <p>{{Session::get('message')}}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                 <h4 class=" mb-0">Change Cricket Match Status</h4>
                  <div class="d-flex align-items-center flex-wrap text-nowrap">
                  <input type="hidden" name="type" id="type" value="p_l">
                  <input class="form-control event-search" id="url"  value="{{url('/')}}" type="hidden" placeholder="Event">

                    {{-- <button type="button" onclick="downloadaccountstatement('admin/account-statement-download')" class="btn btn-primary Refresh btn-icon-text mb-2 mb-md-0">
                      Download CSV
                    </button> --}}

                  </div>
                </div>

                    <form action="{{route('admin-change-cricket-match-status-submit')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mybets-date-picker">
                                <div class="row">
                                    <h5 class="mt-2 mb-2">Active/Inactive This Match <span style="color:red;">( {{ $cricket->team_name }} )</span>:</h5>
                                    <div class="col-lg-1">
                                       <div class="form-check">
                                          <input class="form-check-input" type="radio" name="status" id="active" value="1" {{$currentStatus==1 ? ' checked' : ''}} required>
                                          <label class="form-check-label" for="active">
                                            Activate
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                       <div class="form-check">
                                          <input class="form-check-input" type="radio" name="status" id="inactive" value="0" {{$currentStatus==0 ? ' checked' : ''}} required>
                                          <label class="form-check-label" for="inactive">
                                            Inactivate
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-9"></div>
                                    
                                </div>
                                
                                <div class="row mt-5">
                                    <h5 class="mt-2 mb-2">Win-Loss Setup:</h5>
                                    <div class="col-lg-2">
                                       <div class="form-check">
                                          <input class="form-check-input" type="radio" name="win_loss" id="win_back" value="back" {{$win_loss=='back' ? ' checked' : ''}}>
                                          <label class="form-check-label" for="win_back">
                                            Win Back
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                       <div class="form-check">
                                          <input class="form-check-input" type="radio" name="win_loss" id="win_lay" value="lay" {{$win_loss=='lay' ? ' checked' : ''}}>
                                          <label class="form-check-label" for="win_lay">
                                            Win Lay
                                          </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-8"></div>
                                    
                                    <div class="col-lg-12 text-left">
                                        <label for="toDate" class="col-12 col-form-label w-100 height-24"></label>
                                        <button class="btn btn-primary" style="height: 35px;">SUBMIT</button>
                                    </div>
                                </div>
                                
                                
                            </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$gameid}}" name="game_id">
                        <input type="hidden" value="{{$id}}" name="id">
                    </form>

                  </div>
                </div>
              </div>
            </div>
             {{-- end row --}}
             
             <!--<div class="row">-->
             <!--    <div class="col-md-3">-->
             <!--        <a href="https://crickekbuz.art/cricket/track-match-result" class="btn btn-lg btn-danger">Cricketbuzz Result</a>-->
             <!--    </div>-->
             <!--</div>-->
            
            
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

