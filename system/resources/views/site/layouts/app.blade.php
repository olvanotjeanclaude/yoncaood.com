<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <link href="img/favicon.ico" rel="icon" />
    <link rel="apple-touch-icon" sizes="57x57" href="img/fav/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/fav/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/fav/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/fav/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/fav/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/fav/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/fav/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/fav/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/fav/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/fav/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/fav/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/fav/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/fav/favicon-16x16.png">

    {{-- <link rel="manifest" href="img/fav/manifest.json"> --}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="img/fav/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | Lüks Yonca Ood</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/font-awesome/css/all.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/vendor/owl.carousel/assets/owl.carousel.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/jquery-ui/jquery-ui.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/vendor/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/stylesheet.css') }}" />
    <style>
        .is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            font-size: 0.9em;
            margin-top: 5px;
        }

        #content {
            margin-top: 2rem;
            margin-bottom: 5rem;;
            min-height: 400px;
        }
    </style>

    @yield('css')

</head>

<body>

    <div id="main-wrapper">

        @include('site.includes.header')

        @yield('content')


        @include('site.includes.footer')

        <!-- Script -->
        <script src="{{ asset('public/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('public/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('public/vendor/bootstrap-spinner/bootstrap-spinner.js') }}"></script>
        <script src="{{ asset('public/vendor/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('public/vendor/daterangepicker/daterangepicker.js') }}"></script>
        <script src="{{ asset('public/js/theme.js') }}"></script>
        <script src="{{ asset('public/js/fnGeneral.js') }}"></script>
         <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js?hl=tr&onload=initRecaptcha&render=explicit" async defer></script>

        <script>
            let loginWidgetId;
            let registerWidgetId;

            function initRecaptcha() {
                loginWidgetId = grecaptcha.render('loginRecaptcha', {
                    'sitekey': '{{ env('GRSITEKEY') }}'
                });

                registerWidgetId = grecaptcha.render('registerRecaptcha', {
                    'sitekey': '{{ env('GRSITEKEY') }}'
                });
            }
        </script>

        <script>
            $(function() {
                $('.country-item').on('click', function(e) {
                    e.preventDefault();

                    const code = $(this).data('code');
                    const name = $(this).data('name');

                    $('#selectedCountry').text(code);
                    $('#selectedCountryIcon').html(
                        `<img src="https://flagcdn.com/w20/${code.toLowerCase()}.png">`)
                    $('#selectedFlag').attr(
                        'src',
                        `https://flagcdn.com/w20/${code.toLowerCase()}.png`
                    );
                    $('#countryCode').val(code);

                    $.post('/language-switch', {
                        locale: code.toLowerCase(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function() {
                        location.reload();
                    });
                });
            });
        </script>



        <script>
            $(function() {
                $('#travelDate').daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    minDate: moment(),
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD.MM.YYYY'
                    }
                }, function(chosen_date) {
                    $('#travelDate').val(chosen_date.format('DD.MM.YYYY'));
                });
            });

            $(document).ready(function() {
                $('.signin-btn').on('click', function() {
                    $('#loginModal').modal('show');
                });

                $('.signup-btn').on('click', function() {
                    $('#signupModal').modal('show');
                });

                $('.close-modal').on('click', function() {
                    var modalId = $(this).data('modal');
                    $('#' + modalId).modal('hide');
                });

                $('.card-link').on('click', function() {
                    if ($(this).data('target-cities')) {
                        var targetCities = $(this).data('target-cities').replaceAll(' ', '');
                        var departureCity = "";
                        var arrivalCity = "";
                        var allDestinations = $('#destination option');
                        var allOrigins = $('#origin option');
                        if (targetCities.includes('-')) {
                            var dzTargetCities = targetCities.split('-');
                            departureCity = dzTargetCities[0];
                            arrivalCity = dzTargetCities[1];
                            allDestinations.each(function() {
                                if ($(this).text().includes(departureCity)) {
                                    $(this).prop('selected', true);
                                }
                            });

                            allOrigins.each(function() {
                                if ($(this).text().includes(arrivalCity)) {
                                    $(this).prop('selected', true);
                                }
                            });

                        } else {
                            departureCity = targetCities;
                            allDestinations.each(function() {
                                if ($(this).text().includes(departureCity)) {
                                    $(this).prop('selected', true);
                                }
                            });
                        }
                    }
                });

                if (window.innerWidth < 768) {
                    $('.user-dropdown').html('<i class="fas fa-user"></i>');
                }

            });
        </script>

        @yield('scripts')
        @stack('scripts')
</body>

</html>
