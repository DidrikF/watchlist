<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Company Watchlist</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">

        <link href="/css/welcome.css" rel="stylesheet">

    </head>




    <body>
        <div class="">
            


            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif


            <div id="banner">
                <span>Welcome to<br>Company Watchlist</span>        
            </div>
    
            <div class="short-text">
                Find, review and keep track of companies by adding them to your watchlists. Easily define metric limits to trigger notifications on movements in the market or company fundimentals.
            </div>

            <div class="guide cf">
                <div class="three-col-1">
                    <h3>1. Find a company</h3>
                    <img src="/images/watchlist_search.jpg">
                </div>
                <div class="three-col-2">
                    <h3>2. Analyze the company</h3>
                    <img src="/images/watchlist_analyze.jpg">
                </div>
                <div class="three-col-3">
                    <h3>3. Set up notifications</h3>
                    <img src="/images/watchlist_notification.png">
                </div>
            </div>

        </div>

        <footer>
            <div>
                <span class="copy">&copy; Fleischer Holdings</span>
                <span class="created-by">Created by: Didrik Fleischer</span>
                <a class="sm-link" href="#">facebook</a>
                <a class="sm-link" href="#">Linkedin</a>
                <a class="sm-link" href="#">twitter</a>
            </div>
            
        </footer>
    </body>
</html>
