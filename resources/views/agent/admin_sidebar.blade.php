<ul class="nav">
         <li class="nav-item">
            <a href="{{route('admin-website-dashboard')}}" class="nav-link"> Websites Dashboard </a>
          </li>
    <!---------------------------------------- Game routes cricket/tennis etc --------------------------------->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#CricketGame" role="button" aria-expanded="false" aria-controls="CricketGame">
        <span class="link-title">Cricket Game</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
      </a>
      <div class="collapse" id="CricketGame">
        <ul class="nav sub-menu">
          <li class="nav-item">
            <a href="{{route('admin-cricket-game-create')}}" class="nav-link"> Create Cricket Game</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-cricket-game-list')}}" class="nav-link"> Cricket Game List</a>
          </li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#FootballGame" role="button" aria-expanded="false" aria-controls="FootballGame">
        <span class="link-title">Football Game</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
      </a>
      <div class="" id="FootballGame">
        <ul class="nav sub-menu">
          <li class="nav-item">
            <a href="{{route('admin-football-game-create')}}" class="nav-link"> Create Football Game</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-football-game-list')}}" class="nav-link"> Football Game List</a>
          </li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#TennisGame" role="button" aria-expanded="false" aria-controls="TennisGame">
        <span class="link-title">Tennis Game</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
      </a>
      <div class="collapse" id="TennisGame">
        <ul class="nav sub-menu">
          <li class="nav-item">
            <a href="{{route('admin-tennis-game-create')}}" class="nav-link"> Create Tennis Game</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-tennis-game-list')}}" class="nav-link"> Tennis Game List</a>
          </li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#HorseRacingGame" role="button" aria-expanded="false" aria-controls="HorseRacingGame">
        <span class="link-title">Horse Racing Game</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
      </a>
      <div class="collapse" id="HorseRacingGame">
        <ul class="nav sub-menu">
          <li class="nav-item">
            <a href="{{route('admin-horseracing-game-create')}}" class="nav-link"> Create Horse Racing Game</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-horseracing-game-list')}}" class="nav-link"> Horse Racing Game List</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-horseracing-time-slot-create')}}" class="nav-link"> Create Time Slot</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-horseracing-time-slot-list')}}" class="nav-link"> Time Slot List</a>
          </li>
        </ul>
      </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#GreyhoundRacingGame" role="button" aria-expanded="false" aria-controls="GreyhoundRacingGame">
        <span class="link-title">GreyHound Racing Game</span>
        <i class="link-arrow" data-feather="chevron-down"></i>
      </a>
      <div class="collapse" id="GreyhoundRacingGame">
        <ul class="nav sub-menu">
          <li class="nav-item">
            <a href="{{route('admin-greyhoundracing-game-create')}}" class="nav-link"> Create GreyHound Racing Game</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-greyhoundracing-game-list')}}" class="nav-link"> GreyHound Racing Game List</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-greyhoundracing-time-slot-create')}}" class="nav-link"> Create Time Slot</a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin-greyhoundracing-time-slot-list')}}" class="nav-link"> Time Slot List</a>
          </li>
        </ul>
      </div>
    </li>

    <!------------------------------------------------------------------------------------------------------------->
    
   
</ul>