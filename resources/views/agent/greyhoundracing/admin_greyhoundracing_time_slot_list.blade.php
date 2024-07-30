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
                 <h4 class=" mb-0">GreyHound Racing Time Slot List</h4>
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
                            <th scope="col">Time Slot</th>
                            <th scope="col">Game Title</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($greyhoundracings as $d)
                            <tr>
                                <th scope="row">{{$loop->index+1}}</th>
                                <td><a href="/admin/greyhoundracing/match-create/{{$d->id}}" @if($d->status==1) style="color:orange !important;" @else style="color:red !important;" @endif >{{ $d->time_slot }}</a></td>
                                <td>{{$d->game->game_title}}</td>
                                <td>{{$d->created_at}}</td>
                                <td>{{ $d->status==0? 'Inactive' : 'Active' }}</td>
                                @if($d->status===0)
                                    <td><a href="{{route('activate.greyhoundracing.timeslot.status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(68, 97, 242);">Activate</a></td>
                                @else
                                        <td><a href="{{route('deactivate.greyhoundracing.timeslot.status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(240, 36, 36);">Deactivate</a></td>
                                @endif
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                    <p>{{$greyhoundracings->links()}}</p>
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
