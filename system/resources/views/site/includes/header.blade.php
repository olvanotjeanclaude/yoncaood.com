<header id="header">
    <div class="container-fluid cont-ust">
        <div class="container">
            <div class="row row-ust">
                <div class="col-6 col-lg-4 col-md-4">
                    <div class="social-media">
                        <a class="h4" target="_blank" href="https://www.instagram.com/luksyoncaeurope/"><i
                                class="fab fa-instagram"></i></a>

                        <a href="https://www.facebook.com/groups/solak.aida/" target="_blank" class="h4 ms-3"><i
                                class="fab fa-facebook"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-8 col-md-8">
                    <div class="d-flex flex-wrap justify-content-end align-items-center gap-3">
                        <div>
                            <a class="btn btn-sm btn-light" href="{{ route('pnrSearch') }}">
                                <i class="fas fa-ticket-alt"></i> {{ __("messages.pnr.title") }}
                            </a>
                        </div>
                        <div class="text-header">
                            <span class="iletisim-p text-white">{{ __('messages.contact-gorukle') }}<br></span>
                            <a class="phone-style" href="tel:+905396267500">
                                <i class="fas fa-phone"></i>
                                <span> 0539 626 7500</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="header-row">
            <div class="header-column justify-content-start">

                <div class="logo me-2 me-lg-3">
                    <a href="{{ route('index') }}" class="d-flex" title="Lüks Yonca Ood">
                        <img height="50px" src="{{ asset('img/yonca-ood-logo.png') }}" alt="Yonca Ood Logo"
                            class="nav-main-logo" />
                    </a>
                </div>
            </div>

            <div class="header-column justify-content-end ms-auto-mobile ms-md-auto">
                <nav class="primary-menu navbar navbar-expand-lg">
                    <div id="header-nav" class="collapse navbar-collapse">
                        <ul class="navbar-nav">
                            <li class="dropdown">
                                <a href="{{ route('index') }}">{{ __('messages.nav_home') }}</a>
                            </li>

                            <li class="dropdown">
                                <a href="{{ route('aboutus') }}">{{ __('messages.nav_about_us') }}</a>
                            </li>

                            <li class="dropdown">
                                <a href="{{ route('offices') }}">{{ __('messages.offices') }}</a>
                            </li>

                            <li class="dropdown">
                                <a href="{{ route('iletisim') }}">{{ __('messages.contact') }}</a>
                            </li>
                            @guest
                                <!-- <li class="dropdown">
                                            <a href="{{ route('pnrSearch') }}">{{ __('messages.pnr_search') }}</a>
                                        </li> -->
                                <li class="dropdown li-nav">
                                    <button class="signup-btn"><i class="fa fa-user-plus"></i>
                                        {{ __('messages.register_text') }}</button>
                                </li>
                                <li class="dropdown li-nav">
                                    <button class="signin-btn"><i class="fa fa-unlock"></i>
                                        {{ __('messages.login_text') }}</button>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </nav>

                <div class="dropdown ms-3">
                    <button
                        class="btn btn-link shadow-none border-0 bg-transparent dropdown-toggle d-flex align-items-center p-2 gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedCountryIcon"><img src="https://flagcdn.com/w20/{{ app()->getLocale() }}.png">
                        </span>
                        <span id="selectedCountry" style="width:30px">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item country-item d-flex align-items-center gap-2" href="#"
                                data-code="TR" data-name="Türkçe">
                                <img src="https://flagcdn.com/w20/tr.png"> Türkçe
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item country-item d-flex align-items-center gap-2" href="#"
                                data-code="BG" data-name="🇧Български">
                                <img src="https://flagcdn.com/w20/bg.png"> 🇧Български
                            </a>
                        </li>
                    </ul>
                </div>

                <input type="hidden" id="countryCode" name="country" value="TR">


                <!-- <form action="{{ route('language.switch') }}" method="POST" class="d-inline-block float-right">
                    @csrf
                    <select class="lang-select form-control lang-selectS" id="select-lang" name="locale" onchange="this.form.submit()">
                        <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }}>Türkçe</option>
                        <option value="bg" {{ app()->getLocale() == 'bg' ? 'selected' : '' }}>🇧Български</option>
                    </select>
                </form> -->

                @auth
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle fs-15 ms-3 px-3 user-dropdown" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.welcome_message') }}, {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Çıkış
                                </a>

                                <form id="logout-form" action="/logout" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                            <li><a class="dropdown-item" href="/bilgilerim">{{ __('messages.my_infos_text') }}</a></li>
                            <li><a class="dropdown-item" href="/biletlerim">{{ __('messages.my_tickets') }}</a></li>
                        </ul>
                    </div>
                @endauth

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#header-nav">
                    <span></span> <span></span> <span></span>
                </button>
            </div>
        </div>
    </div>
</header>
