<body>
  <div class="fullpage-loader">
    <div class="fullpage-loader__logo">
      <img src="{{asset('frontend/assets/images/logo-login.png')}}" />
    </div>
  </div>
  <div class="main_menu">
    <div class="Home-pages_marquee">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <marquee class="marquee">
              <div>Greetings, We are happy to announce that we have bring all new security feature for all admin and
                user panels. We request all to make full use of this feature and avoid any fraudulent transaction.
              </div>
            </marquee>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('frontend/assets/images/logo.png')}}" alt="logo" class="img-fluid" /></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 new_menu_color">
            <li class="nav-item dropdown d-flex ml-40">
              <div id="date"></div>
              <div id="time"></div>
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                ( +05:30 )
              </a>
              <ul class="dropdown-menu menu_design_1">
                <li><a class="dropdown-item" href="#">System time - (GMT +00:00)</a></li>
                <li><a class="dropdown-item" href="#">Your computer time - (GMT +05:30)</a></li>
                <li><a class="dropdown-item" href="#">India Standard time - (GMT +05:30)</a></li>
              </ul>
            </li>
          </ul>
          <form class="d-flex menu_serch" role="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="form-control me-2 cus_serch" type="search" placeholder="Search Events" aria-label="Search">
          </form>
          <ul class="navbar-nav  new_menu_color">
            <li class="nav-item mr-30 ml-30">
              @php
              $role=Auth::guard('client')->user()->first_name;
              $lastlogin=Auth::guard('client')->user()->last_login;
              @endphp
              <p>Logged in as {{$role}}</p>
              <p class="last-login">Last logged in:<span>{{ \Carbon\Carbon::parse($lastlogin)->format('d/m/Y H:i:s') }}</span></p>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#RulesstaticBackdrop"><i class="fa-solid fa-ruler-vertical"></i> Rules</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-user"></i> Account
              </a>
              <ul class="dropdown-menu menu_design_1">
                <li><a class="dropdown-item" href="{{route('my-bets')}}">My Bets</a></li>
                <li><a class="dropdown-item" href="{{route('profit_loss')}}">Betting Profit and Loss</a></li>
                <li><a class="dropdown-item" href="{{route('account_statement')}}">Account Statement</a></li>
                <li><a class="dropdown-item" href="{{route('transferstatement')}}">Transfer Statement</a></li>
                <li><a class="dropdown-item" href="{{route('changepassword')}}">Change Password</a></li>
                <li><a class="dropdown-item" href="{{route('secureauth')}}">Secure Auth</a></li>
                <li><a class="dropdown-item" href="{{route('message')}}">Message</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route('client-logout')}}"><i class="fa-solid fa-person-running"></i>LogOut</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>