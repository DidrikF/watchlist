<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.0/css/bulma.css">

        <title>Notification</title>
    </head>
    <body>
        <div>
            <h1 class="title is-3">
                {{ $user->name }}, one of your notifications was triggered!
            </h1>

            <div>
                @if($notification)
                    <h3 class="title is-4">The following notification has been triggered: {{ $notification->name }} </h3>

                    @if($notification->description)
                        <h5 class="title is-5">Notification Description:</h5>
                        <p>{{ $notification->description }}</p>
                    @endif

                @endif
                @if($conditions)
                    <h5 class="title is-5">Notification {{ str_plural('Condition', count($conditions)) }}:</h5>

                    <ul>
                    @foreach($conditions as $condition)
                        <li>{{ $yahooKeyTranslation[$condition->data_id] }} is now
                            @if($condition->comparison_operator == '>')
                                greater than
                            @elseif($condition->comparison_operator == '>')
                                less than
                            @endif
                            {{ $condition->data_value }}
                        </li>
                    @endforeach
                    </ul>
                @endif


                <p>Kind regards from <a href="{{ env('APP_URL') }}">Company Watchlist</a></p>
            </div>
        </div>
    	
    </body>
</html>