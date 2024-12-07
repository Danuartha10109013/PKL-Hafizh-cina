<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('demo5/src/images/faviconlogo.png') }}">
    <!-- Page Title  -->
    <title>@yield('title')</title>
    <!-- StyleSheets  -->

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="./assets/css/libs/fontawesome-icons.css">
    <link rel="stylesheet" href="{{ asset('demo5/src/assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('demo5/src/assets/css/theme.css?ver=3.0.3') }}">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>

    <style>
        .img-placeholder {
            width: 100px;
            /* Lebar lingkaran */
            height: 100px;
            /* Tinggi lingkaran */
            background-color: #2c3e50;
            /* Warna latar belakang */
            border-radius: 50%;
            /* Membentuk lingkaran */
            display: flex;
            /* Flexbox */
            align-items: center;
            /* Pusatkan konten secara vertikal */
            justify-content: center;
            /* Pusatkan konten secara horizontal */
            margin: 0 auto;
            /* Pusatkan lingkaran secara horizontal dalam card-body */
        }

        .img-placeholder::before {
            content: "No Image";
            color: #ffff;
            font-size: 14px;
            text-align: center;
        }
    </style>

</head>

<body class="nk-body bg-grey has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main">
            <!-- sidebar @s -->
            @include('layout.navbar')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap">
                <!-- main header @s -->
                @include('layout.topbar')
                <!-- main header @e -->
                <!-- content @s -->
                @yield('content')
                <!-- content @e -->
                <!-- footer @s -->
                @include('layout.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- select region modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="region">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                <div class="modal-body modal-body-md">
                    <h5 class="title mb-4">Select Your Countryy</h5>
                    <div class="nk-country-region">
                        <ul class="country-list text-center gy-2">
                        </ul>
                    </div>
                </div>
            </div><!-- .modal-content -->
        </div><!-- .modla-dialog -->
    </div><!-- .modal -->
    <!-- JavaScript -->
    <script src="{{ asset('demo5/src/assets/js/bundle.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('demo5/src/assets/js/scripts.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('demo5/src/assets/js/charts/gd-default.js?ver=3.0.3') }}"></script>

</body>

</html>
