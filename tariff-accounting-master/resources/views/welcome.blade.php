<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="author" content="Azizbek Eshonaliyev Nurmamat o'g'li"/>
        <meta name="author" content="Dilmurod Yuldashev"/>
        <link rel="shortcut icon" href="/favicon.ico">
        <title>MUDOFA</title>
        <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/bek96.css') }}">
        <link rel="stylesheet" href="/assets/font/flaticon.css">
    </head>
    <body>
        <div id="app"></div>
    </body>
    <script src="{{ mix('/js/app.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</html>
