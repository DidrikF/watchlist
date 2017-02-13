<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.0/css/bulma.css">

        <title>A user registered</title>
    </head>
    <body>
        <div>
            <h1 class="title is-3">
                @if($gotAccepted)
                    {{ $user->name }}, we are happy to inform that your registration request to Company Watchlist was accepted!
                @else
                    {{ $user->name }}, we regret to inform that your registration request to Company Watchlist was declined.
                @endif
            </h1>
            
            <p>Kind regards from <a href="{{ env('APP_URL') }}">Company Watchlist</a></p>

        </div>
    	
    </body>
</html>