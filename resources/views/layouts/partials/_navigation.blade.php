<div class="cf">
<nav class="top-nav">
    <!-- Logo -->
    <div class="nav-logo">
        <a class="" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>
    </div>

    <!-- Search bar -->    
    <div class="nav-search">
        <form  action="/search" method="get">
            <div>
                <input type="text" name="q" id="search" placeholder="Company name/ticker" value="{{ Request::get('q') }}">
            </div>
            <button type="submit">Search</button>    
        </form>
    </div>


    <!-- Right Side Of Navbar -->
    <div class="nav-menu">
                           
        <!-- Authentication Links -->
        @if (Auth::guest())
            <a href="{{ url('/login') }}">Login</a>
            <a href="{{ url('/register') }}">Register</a>
        @else
            <a href="#" class="">Welcome {{ Auth::user()->name }}!</a>
            <a class="" href="{{ url('/home') }}">Home</a>
            <a href="{{ url('/logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endif
    </div>
</nav>
</div>