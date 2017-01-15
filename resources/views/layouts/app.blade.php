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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.3.0/css/bulma.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    

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

    <div id="app" class="app container is-clearfix">  <!-- We bind our Vue instance to this element -->
        
        @include ('layouts.partials._bulmaNavigation')

        @yield('content')

    </div>
    <footer class="footer is-clearfix" style="bottom: 0; margin-top: 25px;">
        <div class="container has-text-centered">
            <p>
                <strong>Company watchlist</strong> by <a href="https://github.com/DidrikF">Didrik Fleischer</a>. The site is powered by <a href="https://laravel.com/">Laravel</a>, <a href="https://vuejs.org/">Vue.js</a> and <a href="http://bulma.io/">Bulma</a>.
            </p>
            <p style="margin-top: 15px;">
                The source code is available on <a href="https://github.com">github</a>.
            </p>
            <p class="has-text-centered" style="margin-top: 25px;">
                <span class="icon">
                    <a href="https://www.linkedin.com/in/didrik-fleischer-a6623533"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                </span>
                &nbsp;
                <span class="icon">
                    <a href="https://github.com/DidrikF"><i class="fa fa-github" aria-hidden="true"></i></a>
                </span>
                &nbsp;
                <span class="icon">
                    <a href="https://twitter.com/FleischerDidrik"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </span>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src='//cloud.tinymce.com/stable/tinymce.min.js'></script>
    <script src="/js/app.js"></script>
</body>
</html>
