@extends('site.layouts.app')

@section('title', 'Seferler')

@section('content')
    <section class="page-header page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>{{ __('messages.bus_ticket') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div id="content">
        <section class="container cont-shadow">
            <div class="bg-white shadow-lg rounded p-4">
                <div class="row g-3 align-items-center p-10">
                    {{-- Form Section --}}
                    <div class="col-lg-3">
                        <select name="origin" class="form-select" id="origin" required>
                            <option value="" {{ $origin == '' ? 'selected' : '' }}>Nereden</option>
                            @foreach (App\Models\Location::all() as $location)
                                <option value="{{ $location->code }}" {{ $origin == $location->code ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select name="destination" class="form-select" id="destination" required>
                            <option value="" {{ $destination == '' ? 'selected' : '' }}>Nereye</option>
                            @foreach (App\Models\Location::all() as $location)
                                <option value="{{ $location->code }}"
                                    {{ $destination == $location->code ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2">
                        @php
                            try {
                                $travelDateDisplay = !empty($travelDate)
                                    ? \Carbon\Carbon::createFromFormat('m-d-Y', $travelDate)->format('d.m.Y')
                                    : '';
                            } catch (\Exception $e) {
                                $travelDateDisplay = $travelDate ?? '';
                            }
                        @endphp
                        <div class="position-relative">
                            <input id="travelDate" name="travel_date" type="text" class="form-control"
                                placeholder="GİDİŞ TARİHİ" value="{{ $travelDateDisplay }}" required>
                            <span class="icon-inside">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
                            <div class="d-flex gap-2">
                                <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1"
                                    onclick="setToday()" title="Bugün">
                                    <i class="fas fa-arrow-left"></i> <span>{{ __('messages.today') }}</span>
                                </button>

                                <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1"
                                    onclick="setTomorrow()" title="Yarın">
                                    <span>{{ __('messages.tomorrow') }}</span> <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>

                            <button class="btn btn-primary w-100 w-lg-auto"
                                id="seferSorgulaBtn">{{ __('messages.search_trip') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <br><br>

            <div class="row">
                <div class="col-lg-12 mt-lg-0">
                    <div class="hotels-list">
                        @foreach ($travels as $travel)
                            <div class="hotels-item bg-white shadow-md rounded p-3"
                                data-travel-id="{{ $travel->travel_id }}" data-origin="{{ $origin }}"
                                data-destination="{{ $destination }}" onclick="toggleBookingDetails(this)">
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="#"><img class="img-fluid-1 rounded align-top"
                                                src="{{ asset('img/yonca-ood-logo.png') }}" alt="logo"></a>
                                    </div>
                                    <div class="col-md-7 ps-3 ps-md-0 mt-3">
                                        <div class="row g-0 row-seyahat">
                                            <div class="col-md-2">
                                                <h4><a href="#"
                                                        class="text-dark text-5">{{ $travel->locationtime }}</a></h4>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-2">
                                                    <span class="text-black-50 travel-loc">
                                                        <i class="fas fa-map-marker-alt"></i> {{ $travel->routename }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-2">
                                                <p class="hotels-amenities d-flex align-items-center mb-2 text-4">
                                                    <span data-bs-toggle="tooltip" title='{{ __('messages.priz') }}'><i
                                                            class="fas fa-plug"></i></span>
                                                    <span data-bs-toggle="tooltip" title='{{ __('messages.wifi') }}'><i
                                                            class="fas fa-wifi"></i></span>
                                                    <span data-bs-toggle="tooltip"
                                                        title='{{ __('messages.phone_tooltip') }}'><i
                                                            class="fas fa-phone"></i></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end d-flex d-sm-block align-items-center">
                                        <div class="text-success text-3 mb-0 mb-sm-1 order-2 fw-500 tarihStyle">
                                            {{ \Carbon\Carbon::parse($travel->locationdate)->format('d.m.Y H:i') }}
                                        </div>

                                        <form action="{{ route('seyehatsec') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="travelId" value="{{ $travel->travelid }}">
                                            <input type="hidden" name="origin" value="{{ $origin }}">
                                            <input type="hidden" name="destination" value="{{ $destination }}">
                                            <input type="hidden" name="travel_date" value="{{ $travel->locationdate }}">

                                            <button type="submit" class="btn btn-sm btn-primary order-4 ms-auto">
                                                {{ __('messages.select') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        function formatDateTr(date) {
            return date.getDate().toString().padStart(2, '0') + '.' +
                (date.getMonth() + 1).toString().padStart(2, '0') + '.' + date.getFullYear();
        }

        function setToday() {
            document.getElementById('travelDate').value = formatDateTr(new Date());
        }

        function setTomorrow() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('travelDate').value = formatDateTr(tomorrow);
        }

        $(document).ready(function() {
            $('#seferSorgulaBtn').click(function() {
                const destination = $('#destination').val();
                const travelDate = $('#travelDate').val();
                const origin = $('#origin').val();

                // Görünen GG.AA.YYYY değerini URL'nin beklediği AA-GG-YYYY formatına çevir
                const parts = travelDate.split('.');
                const urlDate = parts.length === 3 ? `${parts[1]}-${parts[0]}-${parts[2]}` : travelDate;

                window.location.href = `/sefersorgula/${encodeURIComponent(origin)}/${encodeURIComponent(destination)}/${encodeURIComponent(urlDate)}`;
            });
        });
    </script>
@endsection
