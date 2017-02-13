<nav class="nav has-shadow">
  <div class="nav-left">
    <a class="nav-item title is-4" href="{{ url('/') }}">
      {{ config('app.name') }}
    </a>
  </div>

  <div class="nav-center">
      <form class="nav-item" action="/search" method="get">
          <input class="input" type="text" placeholder="Company/ticker" name="q" value="{{ Request::get('q') }}">
          &nbsp;
          {{ csrf_field() }}
          <button class="button is-outlined" type="submit"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search</button>
      </form>
  </div>

  <!-- This "nav-toggle" hamburger menu is only visible on mobile -->
  <!-- You need JavaScript to toggle the "is-active" class on "nav-menu" -->

  <!-- Not working -->


  <!-- This "nav-menu" is hidden on mobile -->
  <!-- Add the modifier "is-active" to display it on mobile -->
  <div class="nav-right">

    @if (Auth::guest())
      <a class="nav-item is-tab" href="{{ url('/login') }}">Login</a>
      <a class="nav-item is-tab" href="{{ url('/register') }}">Register</a>
    @else
      <a class="nav-item is-tab" href="{{ url('/home') }}">Home</a>
      @if(Auth::check())
        @if(Auth::user()->isAdmin())
          <a class="nav-item is-tab" href="{{ url('/admin/panel') }}">Admin Panel</a>
        @endif
      @endif
      <a class="nav-item is-tab is-3" href="{{ url('/logout') }}" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
      </a>

      <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
    @endif

  </div>
</nav>