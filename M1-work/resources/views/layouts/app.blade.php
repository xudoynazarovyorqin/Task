<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/datepicker.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/mini.css" rel="stylesheet" type="text/css">

    <!-- Styles -->
    {{-- <link href="{{ asset(    'css/app.css') }}" rel="stylesheet"> --}}
</head>
<body>

    <div class="wrapper  collapses">


        <div class="top_navbar">
            <div class="hamburger">
                <div class="one"></div>
                <div class="two"></div>
                <div class="three"></div>
            </div>
            <div class="top_menu">
                <div class="logo">A1 CRM</div>
                <ul>
                    <li>
                        <a href="https://t.me/barm_admin">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/profile.php?id=100074643827159">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/barm_uz/">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
                    <li>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-lock"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ///////////////////////////////////////////// end top_navbar ///////////////////////////////////////////// -->

        <div class="sidebar">
            <ul>
                <li>
                    <a href="/home" class="{{ Request::segment(1) === 'home' ? 'active' : null }}">
                        <span class="icon">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="title">Dashboards</span>
                    </a>
                </li>
                <li>
                    <a href="/coming" class="{{ Request::segment(1) === 'coming' ? 'active' : null }}">
                        <span class="icon"><i class="fas fa-box-open"></i></span>
                        <span class="title">Приход</span>
                    </a>
                </li>
                <li>
                    <a href="/providers" class="{{ Request::segment(1) === 'providers' ? 'active' : null }}">
                        <span class="icon"><i class="fas fa-people-carry"></i></span>
                        <span class="title">Поставщики</span>
                    </a>
                </li>
                <li>
                    <a href="/transactions" class="{{ Request::segment(1) === 'transactions' ? 'active' : null }}">
                        <span class="icon"><i class="fas fa-hand-holding-usd"></i></span>
                        <span class="title">Касса</span>
                    </a>
                </li>
                <li>
                    <a href="/products" class="{{ Request::segment(1) === 'products' ? 'active' : null }}">
                        <span class="icon"><i class="fas fa-cogs"></i></span>
                        <span class="title">Настройки</span>
                    </a>
                </li>


            </ul>
        </div>
        <!-- ///////////////////////////////////////////// end sidebar left /////////////////////////////////////////// -->

        <div class="main_container">

            @yield('content')

        </div>

        <!-- ///////////////////////////////////////////// end main_container center ////////////////////////////////// -->
    </div>


    <!-- ///////////////////////////////////////////// end main_container center ////////////////////////////////// -->
</div>
<script src="js/bilmadim.js"></script>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/datepicker.min.js"></script>
<script defer src="js/all.js"></script>
<script>
    $(document).ready(function () {
        $(".hamburger").click(function () {
            $(".wrapper").toggleClass("collapses");
        });
    });
</script>
</body>
</html>
