<div class="cf">
<nav class="top-nav">
    <!-- Logo -->
    <div class="nav-logo">
        <a class="" href="{{ url('/livedemo/companywatchlist') }}">
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
            <a href="{{ url('/livedemo/companywatchlist/login') }}">Login</a>
            <a href="{{ url('/livedemo/companywatchlist/register') }}">Register</a>
        @else
            <a href="#" class="">Welcome {{ Auth::user()->name }}!</a>
            <a class="" href="{{ url('/livedemo/companywatchlist/home') }}">Home</a>
            <a href="{{ url('/livedemo/companywatchlist/logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ url('/livedemo/companywatchlist/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        @endif
    </div>
</nav>
</div>