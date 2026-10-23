<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('content')

<div id="content">


    <section id="banner-slider">
        <div width="100%">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                        class="active"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/yonca-ood1.png" class="d-block w-100" alt="Slider 1">
                    </div>
                    <!-- <div class="carousel-item">
                                      <img src="img/yonca-ood2.png" class="d-block w-100" alt="Slider 2">
                                    </div> -->
                    <div class="carousel-item">
                        <img src="img/yonca-ood5.png" class="d-block w-100" alt="Slider 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>
    <!-- <div class="owl-carousel owl-theme single-slider" data-animateout="fadeOut" data-animatein="fadeIn" data-autoplay="true" data-loop="true" data-autoheight="true" data-nav="true" data-items="1">
                                        <div class="item"><a href="#"><img width="100px" class="img-fluid" src="https://i.hizliresim.com/gulx2a9.jpeg" alt="banner 7" /></a></div>
                                      </div> -->
    <div class="div-search">
        <section class="container">
            <div class="rounded p-4 bg-p">
                <p class="seferTitle">{{ __('messages.flight_search') }}</p>
                <div class="row g-3 align-items-center">
                    <!-- Nereden (From) -->
                    <div class="col-lg-3">
                        <label class="fnt-white">{{ __('messages.from') }}</label>
                        <div class="input-wrapper">
                            <i class="fa fa-map-marker input-icon input-icon-left"></i>
                            <select name="origin" class="form-select with-icon" id="origin" required>
                                <option value="">{{ __('messages.from') }}</option>
                                @foreach (App\Models\Location::all() as $location)
                                <option value="{{ $location->code }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Nereye (To) -->
                    <div class="col-lg-3">
                        <label class="fnt-white">{{ __('messages.to') }}</label>
                        <div class="input-wrapper">
                            <i class="fa fa-map-marker input-icon input-icon-left"></i>
                            <select name="destination" class="form-select with-icon" id="destination" required>
                                <option value="">{{ __('messages.to') }}</option>
                                @foreach (App\Models\Location::all() as $location)
                                <option value="{{ $location->code }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tarih (Travel Date) -->
                    <div class="col-lg-3 position-relative">
                        <label class="fnt-white">{{ __('messages.departure_date') }}</label>
                        <div class="input-wrapper">
                            <i class="fa fa-calendar input-icon input-icon-highlight input-icon-left"></i>
                            <input id="travelDate" name="travel_date" type="text" class="form-control with-icon"
                                placeholder="{{ __('messages.departure_date') }}" autocomplete="off" required>
                            <span class="icon-inside"
                                style="position:absolute; right:10px; top:35%; transform:translateY(-50%);">
                                <i class="far fa-calendar-alt calStyle"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Buton -->
                    <div class="col-lg-3 d-grid">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary" id="seferSorgulaBtn"
                            type="button">{{ __('messages.search') }}</button>
                    </div>
                </div>
            </div>
        </section>

    </div>


    @push('scripts')
    <script>
        $(function() {
            $('#seferSorgulaBtn').on('click', function() {
                const destination = $('#destination').val();
                const origin = $('#origin').val();
                const travelDate = $('#travelDate').val();

                if (!destination || !origin || !travelDate) {
                    alert('Lütfen tüm alanları doldurunuz!');
                    return;
                }

                // Görünen GG.AA.YYYY değerini URL'nin beklediği AA-GG-YYYY formatına çevir
                const parts = travelDate.split('.');
                const urlDate = parts.length === 3 ? `${parts[1]}-${parts[0]}-${parts[2]}` : travelDate;

                const queryUrl =
                    `/sefersorgula/${encodeURIComponent(origin)}/${encodeURIComponent(destination)}/${encodeURIComponent(urlDate)}`;

                window.location.href = queryUrl;
            });
        });
    </script>
    @endpush
</div>


<section class="ozellik-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="featured-box text-center">
                    <div class="featured-box-icon"> <i class="fas fa-lock"></i> </div>
                    <h4>{{ __('messages.secure_payment.title') }}</h4>
                    <p>{{ __('messages.secure_payment.text') }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="featured-box text-center">
                    <div class="featured-box-icon"> <i class="fas fa-thumbs-up"></i> </div>
                    <h4>{{ __('messages.travel_insurance.title') }}</h4>
                    <p>{{ __('messages.travel_insurance.text') }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="featured-box text-center">
                    <div class="featured-box-icon"> <i class="fas fa-bullhorn"></i> </div>
                    <h4>{{ __('messages.customer_satisfaction.title') }}</h4>
                    <p>{{ __('messages.customer_satisfaction.text') }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="featured-box text-center">
                    <div class="featured-box-icon"> <i class="far fa-life-ring"></i> </div>
                    <h4>{{ __('messages.safe_transport.title') }}</h4>
                    <p>{{ __('messages.safe_transport.text') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="neden-biz">
    <div class="container main-container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <img src="img/yonca-ood-r.jpg" alt="Lüks Yonca OOD" class="img-fluid rounded1">
            </div>

            <div class="col-md-6">
                <h5 class="small-title">
                    {{ __('messages.why_us_title_small') }}
                </h5>

                <h3 class="main-title">
                    {{ __('messages.why_us_title_main_1') }}
                    <span>{{ __('messages.why_us_title_main_2') }}</span>
                    {{ __('messages.why_us_title_main_3') }}
                </h3>

                <p>
                    {{ __('messages.why_us_description') }}
                </p>

                <div class="row neden-biz-stats mt-4">

                    <div class="col-md-6 mb-3">
                        <div class="stat-item">
                            <i class="fas fa-bus"></i>
                            <div>
                                <strong>{{ __('messages.why_us_stat_1_title') }}</strong>
                                <span>{{ __('messages.why_us_stat_1_text') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="stat-item">
                            <i class="fas fa-route"></i>
                            <div>
                                <strong>{{ __('messages.why_us_stat_2_title') }}</strong>
                                <span>{{ __('messages.why_us_stat_2_text') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="stat-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>{{ __('messages.why_us_stat_3_title') }}</strong>
                                <span>{{ __('messages.why_us_stat_3_text') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>{{ __('messages.why_us_stat_4_title') }}</strong>
                                <span>{{ __('messages.why_us_stat_4_text') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>

<div class="container mt-5 py-2">
    <center>
        <h2 class="text-6 fw-500 mb-4">{{ __('messages.featured_trips') }}</h2>
    </center>
    <div class="row gx-3 gy-4 pb-72">
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="İSTANBUL - SOFYA">
                <div class="card border-0 hover-effect">
                    <img src="https://images7.alphacoders.com/374/thumb-1920-374245.jpg" class="card-img-top rounded"
                        alt="ISTANBUL - SOFIA">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">İSTANBUL - SOFIA</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="BURSA - KIRCAALI">
                <div class="card border-0 hover-effect">
                    <img src="https://media.istockphoto.com/id/473634694/tr/foto%C4%9Fraf/hdr-image-of-arda-river.jpg?s=612x612&w=0&k=20&c=PXyFMfLju-YB4TP3kpMar85_ZR5oB9_AAFnBcA7ivsA="
                        class="card-img-top rounded" alt="BURSA-KIRCAALI">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">BURSA - KIRCAALİ</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="MOMÇILGRAD">
                <div class="card border-0 hover-effect">
                    <img src="https://kircaalihaber.com/tr_haber_images1/news_img-20210929-064148.jpg"
                        class="card-img-top rounded" alt="MOMÇİLGRAD">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">MOMÇİLGRAD</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="BURSA - HASKOVO">
                <div class="card border-0 hover-effect">
                    <img src="https://t4.ftcdn.net/jpg/02/56/31/35/360_F_256313585_Rzcl0WXkFHsZORvGy93PiNdKp4AaskNA.jpg"
                        class="card-img-top rounded" alt="BURSA-HASKOVO">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">BURSA - HASKOVO</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="PLOVDİV">
                <div class="card border-0 hover-effect">
                    <img src="img/plovdiv.jpg" class="card-img-top rounded" alt="ÖRNEK">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">PLOVDİV</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="#" class="card-link" data-target-cities="İSTANBUL">
                <div class="card border-0 hover-effect">
                    <img src="img/ist.jpg" class="card-img-top rounded" alt="ÖRNEK">
                    <div class="card-body py-2 px-2">
                        <p class="card-title fw-500 text-dark mb-0 text-center">İSTANBUL</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>


<div class="top-area show-onload">
    <div class="bg-holder">
        <div class="bg-mask"></div>
        <div class="bg-content">
            <div class="container cont-alt">
                <div class="gap gap-big" style="text-align:center">
                    <h2 class="text-uc mb44 text-white">{{ __('messages.top_area.heading') }}</h2>
                    <span class="phone-2">
                        <a class="text-white" href="#">{{ __('messages.top_area.button') }}</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
    });
</script>
@endpush
@endsection