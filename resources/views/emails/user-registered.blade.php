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
                {{ $user->name }} has signed up to Company Watchlist!
            </h1>

            <p>Please visit the <a href="http://watchlist.app/admin/panel">admin panel</a> to review the registration request</p>

            <p>Kind regards from <a href="http://watchlist.app">Company Watchlist</a></p>
        </div>
    	
    </body>
</html>