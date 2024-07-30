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

             {{-- end row --}}
             
            <div class="row">
              <div class="col-12 col-xl-12 grid-margin stretch-card">
                <div class="card overflow-hidden">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3 pb-2 border-bottom">
                     <h4 class=" mb-0">User Match Bet List</h4>
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
                                <th class="text-center">Win/Loss</th>
                                <th class="text-center">Placed Time</th>
                                <th class="text-center">Settled Time</th>
                              </tr>
                            </thead>
                            <tbody>
                            
                             @if(!empty($response['response']))
                             @foreach($response['response'] as $bet)
                            <tr class="back">
                                <td class="text-center">{{$bet['id']}}</td>
                                <td class="text-center">{{$bet['team_name']}}</td>
                                <td class="text-center">{{$game}}</td>
                                <td class="text-center">{{$bet['back_lay']}}</td>
                                <td class="text-center">{{$bet['bet_stake']}}</td>
                               
                                @if($bet['bet_result'] == 1)
                                <td class="text-center green">{{$bet['bet_profit']}}</td>
                                @elseif($bet['bet_result'] == 2)
                                 <td class="text-center red"> {{$bet['bet_stake']}}</td>
                                 @else
                                  <td class="text-center black"> - </td>
                                @endif
                                <td class="text-center">{{$bet['created_at']}}</td>
                                <td class="text-center">{{$bet['created_at']}}</td>
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
            {{-- end row --}}
            
            
          </div>

      </div>
     
    </div>
  </div>


@include('layouts.footer')


