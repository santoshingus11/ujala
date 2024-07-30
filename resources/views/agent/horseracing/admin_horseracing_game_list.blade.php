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
                 <h4 class=" mb-0">Horse Racing Game List</h4>
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
                            <th scope="col">Game Title</th>
                            <th scope="col">Run Date Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($horseracings as $d)
                            <tr>
                                <th scope="row">{{$loop->index+1}}</th>
                                 <td><button class="badge badge-primary edit_btn" style="background-color:rgb(68, 97, 242);" data-match-id={{$d->id}} data-channel-id={{$d->channel_id}}>Edit</button>
                                    <a href="{{route('delete.HorseRacing.game.new',$d->id)}}" class="badge badge-danger">Delete</a>
                                    </td>
                                 <td><a href="{{url('/admin/horseracing/match-create/'.$d->id)}}" @if($d->status==1) style="color:orange !important;" @else style="color:red !important;" @endif >{{ $d->game_title }}</a></td>
                                <!-- <td><span @if($d->status==1) style="color:orange !important;" @else style="color:red !important;" @endif >{{ $d->game_title }}</span></td> -->
                                <td>{{$d->run_date_time}}</td>
                                <td>{{ $d->status==0? 'Inactive' : 'Active' }}</td>
                                @if($d->status===0)
                                    <td><a href="{{route('activate.horseracing.game.status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(68, 97, 242);">Activate</a></td>
                                @else
                                        <td><a href="{{route('deactivate.horseracing.game.status',$d->id)}}" class="badge badge-primary" style="background-color:rgb(240, 36, 36);">Deactivate</a></td>
                                @endif
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                    <p>{{$horseracings->links()}}</p>
                </div>

                <div class="border-bottom mt-3"></div>

              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>






<!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Game</h5>
          <button type="button" class="close" data-dismiss="modal" onclick="closeModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editForm" action="{{ route('update.HorseRacing.game.admin') }}" method="POST">
            @csrf
            <input type="hidden" name="match_id" id="match_id">
            <div class="form-group">
              <label for="game_title" class="col-form-label">Game Title:</label>
              <input type="text" class="form-control" id="game_title" name="game_title" required>
            </div>
             <div class="col-lg-6">
                                        <label for="run_date_time" class="col-12 col-form-label" style="font-weight: bolder;">Run Date/Time</label>
                                        <input name="run_date_time" id="run_date_time" type="text" autocomplete="off" value="{{old('run_date_time')}}" placeholder="yyyy-mm-dd h:m:s" class="mx-input w-100 form-control" autofocus>
            <div class="form-group">
              <label for="game_title" class="col-form-label">Channel:</label>
              <input type="text" class="form-control" id="channel_id" name="channel_id">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Close</button>
          <button type="submit" class="btn btn-primary" form="editForm">Save changes</button>
        </div>
      </div>
    </div>
  </div>



<script>
  document.addEventListener("DOMContentLoaded", function() {
    var editButtons = document.querySelectorAll(".edit_btn");
    
    editButtons.forEach(function(button) {
      button.addEventListener("click", function() {
        var matchId = this.getAttribute("data-match-id");
         var channelId = this.getAttribute("data-channel-id");
        var gameTitle = this.closest("tr").querySelector("td:nth-child(3)").innerText;
        
        // Populate the modal fields
        document.getElementById("match_id").value = matchId;
        document.getElementById("game_title").value = gameTitle;
        document.getElementById("channel_id").value = channelId;

        // Show the modal
        $('#editModal').modal('show');
      });
    });
  });
  
  
  function closeModal(){
      $('#editModal').modal('hide');
  }
  
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  $( function() {
      
    jQuery('#run_date_time').datetimepicker();
    
  } );
</script>





  @include('layouts.footer')
