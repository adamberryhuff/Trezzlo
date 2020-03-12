<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script src="/js/jquery.min.js" defer></script>
        <script src="/js/tether.min.js" defer></script>
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <script src="/js/app.js" defer></script>
    </head>
    <body style="background-color:#f5f5f5;"> 
        <div class="content">
            @yield('content')
        </div>
    </body>
</html>