<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>

        //Sharing data, so vue can access it
        window.watchlist = {
            url: "{{ config('app.url') }}",
            user: {
                id: {{ Auth::check() ? Auth::user()->id : 'null' }},
                authenticated: {{ Auth::check() ? 'true' : 'false' }},
            } 
        };
    </script>
</head>


<body>

    <div id="app">  <!-- We bind our Vue instance to this element -->
        
        @include ('layouts.partials._navigation')

        @yield('content')

    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>
