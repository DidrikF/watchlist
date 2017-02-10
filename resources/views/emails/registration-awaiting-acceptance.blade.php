<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.0/css/bulma.css">

        <title>You registered</title>
    </head>
    <body>
        <div>
            <h1 class="title is-3">
                {{ $user->name }}, your successfully registered to Company Watchlist!
            </h1>

            <p>Before you get access to the site, your registration request will have to be accepted by a site administrator.</p>

            <p>Your registration request will be reviewed shortly.</p>

            <p>Thank you for your patience</p>

            <p>Kind regards from <a href="http://watchlist.app">Company Watchlist</a></p>
        </div>
    	
    </body>
</html>