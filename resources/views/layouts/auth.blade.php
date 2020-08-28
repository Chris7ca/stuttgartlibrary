<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Stuttgart Library - @yield('title_page')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Work+Sans&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

    <main>

        <div class="uk-grid uk-grid-collapse uk-grid-match" uk-grid>
            <div class="uk-width-1-3 uk-visible@m">
                <div class="uk-position-relative uk-background-cover uk-background-center-right" data-src="https://images.pexels.com/photos/238118/pexels-photo-238118.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940" uk-img>
                    
                </div>
            </div>
            <div class="uk-width-2-3@m">
                <div class="uk-position-relative uk-padding-large" uk-height-viewport="offset-top: true">
                    @yield('content_page')
                </div>
            </div>
        </div>

    </main>
    
    <script src="{{ asset('js/uikit.min.js') }}"></script>
    <script src="{{ asset('js/uikit-icons.min.js') }}"></script>
</body>
</html>
