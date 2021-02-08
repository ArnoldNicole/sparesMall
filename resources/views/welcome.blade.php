<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Online Spare Parts Store</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Online Spare Parts Store" name="keywords">
        <meta content="Buy all your Spare parts, Free delivery for select Locations" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Source+Code+Pro:700,900&display=swap" rel="stylesheet">

        <!-- CSS Libraries -->
        <link rel="stylesheet" href="{{asset('/css/app.css')}}">
        <link rel="stylesheet" href="{{asset('vendor/font-awesome/css/all.css')}}">
        <link href="{{asset('/vendor/slick/slick.css')}}" rel="stylesheet">
        <link href="{{asset('/vendor/slick/slick-theme.css')}}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{asset('/vendor/style.css')}}" rel="stylesheet">
        <script src="{{asset('/js/app.js')}}" defer></script>
    </head>
    <body>
        <div id="application">
            @auth
            <main-app :user="{{auth()->user()}}" :cart='@json($cart)'>
            </main-app>
            @else
            <main-app :user="false" :cart="false">
            </main-app>
            @endauth
        </div>
        <script src="{{asset('/vendor/easing/easing.min.js')}}" defer></script>
        <script src="{{asset('/vendor/slick/slick.min.js')}}" defer></script>
        
        <!-- Template Javascript -->
        <script src="{{asset('/vendor/main.js')}}" defer></script>
    </body>
    </html>
