<!-- resources/views/welcome.blade.php -->

@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('css')
    {{-- <link rel="stylesheet" type="text/css" media="all" href="assets/otobus.css"> --}}
    <link rel="stylesheet" type="text/css" media="all" href="https://www.biletara.com/css/custom.css?v=1">
    <link rel="stylesheet" type="text/css" media="all" href="https://www.biletara.com/css/seferler.css?v=1">
    <style>
        @media (max-width: 576px) {
            .bus-seat-area {
                padding-top: 56px !important;
            }
        }
    </style>
@endsection

@section('content')
    <section class="page-header-1 page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>{{ __('messages.seat_selection_title') }}</h1>
                </div>
            </div>
        </div>
    </section>


    <div id="content">
        <section class="container">
            <div class="row">
                <div class="col-lg-12 koltukStyle mb-50">

                    <div class="seat-content">

                        <div class="bus-seat-area">
                            <div class="seat-tour-bus">
                                <div class="tB-front"><i class="fas fa-circle-notch"></i></div>
                                <div class="tB-body">
                                    <div class="bus-seats">
                                        @foreach ($seats['line'] as $line)
                                            <div class="tBb-line">
                                                @php
                                                    // dd($line['seat']);
                                                    $reversedSeats = array_reverse($line['seat']);
                                                    rsort($reversedSeats);
                                                @endphp

                                                @foreach ($reversedSeats as $seat)
                                                    @if ($seat['seatno'] == '00')
                                                        <div class="tBb-cell hall"></div>
                                                    @else
                                                        @php
                                                            $seatClass = 'free';

                                                            $gender = empty($seat['gender']) ? '' : $seat['gender'];

                                                            if ($gender === 'B') {
                                                                $seatClass = 'full-woman';
                                                            } elseif ($gender === 'E') {
                                                                $seatClass = 'full-man';
                                                            }
                                                        @endphp

                                                        <div class="tBb-cell seat {{ $seatClass }}"
                                                            tabindex="{{ $seat['seatno'] }}" role="button"
                                                            onclick="toggleSeat('{{ $seat['seatno'] }}')"
                                                            data-seatno="{{ $seat['seatno'] }}" data-price="50">
                                                            <span
                                                                style="z-index:9999;padding:15px">{{ $seat['seatno'] }}</span>
                                                            <div data-toggle="gender" id="popGender{{ $seat['seatno'] }}"
                                                                class="genderselect" style="display:none">
                                                                <div data-gender="man" data-seatno="{{ $seat['seatno'] }}"
                                                                    onclick="selectGender(this)">
                                                                    <img src="https://www.biletara.com//img/passengerman.png"
                                                                        width="55" alt="Select Man">
                                                                </div>

                                                                <div data-gender="woman" data-seatno="{{ $seat['seatno'] }}"
                                                                    onclick="selectGender(this)">
                                                                    <img src="https://www.biletara.com//img/passengerwoman.png"
                                                                        width="55" alt="Select Woman">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tB-back"></div>
                        </div>
                        <div class="travel-infos"></div>
                    </div>

                    <div class="seat-selects" id="viewSelectSeats">
                        <p class="seat-selects-title secilenKoltuks">{{ __('messages.selected_seats_title') }}</p>
                        <div id="selectedSeats">{{ __('messages.selected_seats') }}</div>
                        <p id="totalPriceDisplay">{{ __('messages.total_price') }} 0 €</p>

                        <div class="x"></div>
                        <button class="btn btn-success btn-travel-payment w-100"
                            onclick="buyTickets()">{{ __('messages.continue') }}
                        </button>
                    </div>

                    <div class="seat-infos" style="position: relative;">

                        <div class="seat-info-area">
                            <div class="seat-info-icon tBb-cell full-woman"></div>
                            <div class="seat-info-text">{{ __('messages.female') }}</div>
                        </div>
                        <div class="seat-info-area">
                            <div class="seat-info-icon tBb-cell full-man"></div>
                            <div class="seat-info-text">{{ __('messages.male') }}</div>
                        </div>
                        <div class="seat-info-area">
                            <div class="seat-info-icon tBb-cell seat"></div>
                            <div class="seat-info-text">{{ __('messages.empty') }}</div>
                        </div>
                        <div class="seat-info-area">
                            <div class="seat-info-icon tBb-cell seat-select"></div>
                            <div class="seat-info-text">{{ __('messages.selected_seat') }}</div>
                        </div>
                        <br><br><br><br>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.seat.full-woman, .seat.full-man').forEach(el => {
            el.onclick = null;
            el.removeAttribute('onclick');
            el.removeAttribute('tabindex');
            el.style.pointerEvents = 'none';
            el.style.cursor = 'not-allowed';
        });

        var selectedSeats = [];
        var totalPrice = 0;

        function updateTotalPriceDisplay() {
            var totalPriceDisplay = document.getElementById('totalPriceDisplay');
            totalPriceDisplay.textContent = '{{ __('messages.total_price') }} ' + totalPrice + ' €';
        }

        function toggleSeat(seatNo, gender) {
            var seatElement = document.querySelector('[data-seatno="' + seatNo + '"]');
            var genderSelect = document.getElementById('popGender' + seatNo);
            var price = {{ $price }}; // Koltuk fiyatını sabit olarak 50 olarak ayarla


            if (seatElement.classList.contains('full-' + gender)) {
                console.log('Seat is reserved for ' + gender + ' and cannot be selected.');
                return;
            }

            // Check if the seat is free
            if (seatElement.classList.contains('free')) {
                // Check if the maximum number of selected seats is reached
                if (selectedSeats.length < 4) {
                    seatElement.classList.remove('free');
                    seatElement.classList.add('selected', 'seat-select');

                    hideOtherGenderPopups(seatNo);

                    genderSelect.style.display = 'block';

                    selectedSeats.push({
                        seatNo: seatNo,
                        gender: gender
                    });


                    var selectedSeatNo = seatElement.getAttribute('data-seatno');
                    // console.log('Selected seat number:', selectedSeatNo);
                    // console.log('Selected seats:', selectedSeats);

                    // Update the total price
                    totalPrice += price;
                    updateTotalPriceDisplay();
                    // console.log('Total price after selection:', totalPrice);
                } else {
                    alert('Maximum number of seats reached (4 seats)');
                }
            } else {
                seatElement.classList.remove('selected', 'seat-select');
                seatElement.classList.add('free');
                genderSelect.style.display = 'none';

                // Find the index of the deselected seat in the array
                var deselectedSeatIndex = selectedSeats.findIndex(seat => seat.seatNo === seatNo);

                // Remove the deselected seat from the array
                if (deselectedSeatIndex !== -1) {
                    selectedSeats.splice(deselectedSeatIndex, 1);
                    totalPrice -= price;
                    updateTotalPriceDisplay();
                    // console.log('Total price after deselection:', totalPrice);
                }
            }
        }

        function hideOtherGenderPopups(selectedSeatNo) {
            $('[data-toggle="gender"]').each(function() {
                var seatNo = $(this).data('seatno');
                if (seatNo !== selectedSeatNo) {
                    $(this).hide();
                }
            });
        }

        function closeOtherPopups(seatNo) {
            // Tüm cinsiyet seçme pop-up'larını bul
            var allPopups = document.querySelectorAll('[data-toggle="gender"]');

            // Diğer pop-up'ları kapat
            allPopups.forEach(function(popup) {
                var popupSeatNo = popup.getAttribute("data-seatno");
                if (popupSeatNo !== seatNo) {
                    popup.style.display = 'none';
                }
            });
        }

        var selectedSeatsAndGenders = [];

        function selectGender(element) {
            var gender = element.getAttribute("data-gender");
            var seatNo = element.getAttribute("data-seatno");
            var genderSelect = document.getElementById('popGender' + seatNo);
            var seatElement = document.querySelector('[data-seatno="' + seatNo + '"]');

            // Diğer pop-up'ları kapat
            closeOtherPopups(seatNo);

            // Kontrol: Rezerve edilmiş koltuklara cinsiyet seçme izni verme
            if (seatElement.classList.contains('reserved')) {
                console.log('Bu koltuk rezerve edilmiş, cinsiyet seçemezsiniz.');
                return;
            }

            // Kontrol: Eğer rezervasyon varsa cinsiyet seçme işlemini devre dışı bırak
            if (seatElement.classList.contains('reserved-man') || seatElement.classList.contains('reserved-woman')) {
                console.log('Bu koltukta rezervasyon var, cinsiyet seçemezsiniz.');
                return;
            }


            toggleSeat(seatNo, gender);

            // Seçilen koltuk ve cinsiyet bilgilerini diziye ekle
            selectedSeatsAndGenders.push({
                seatNo: seatNo,
                gender: gender
            });
            console.log('Selected Seats and Genders:', selectedSeatsAndGenders);

            // Sadece seçilen cinsiyetin pop-up'ını aç
            genderSelect.style.display = 'block';

            // Cinsiyet seçildikten sonra pop-up'ı kapat
            setTimeout(function() {
                genderSelect.style.display = 'none';
            }, 0);
        }

        function buyTickets() {
            const freeSeats = document.querySelectorAll('.seat.free');
            if (freeSeats.length === 0) {
                console.log('Uygun koltuk kalmadı.');
                return;
            }

            // Eğer seçilen koltuk yoksa işlemi gerçekleştirme
            if (selectedSeats.length === 0) {
                console.log('Hiç koltuk seçilmedi. Lütfen koltuk seçiniz.');
                return;
            }

            console.log('Satın alınan koltuklar:', selectedSeats);

            // Diğer verileri al
            var travelId = {{ $travelId }};
            var origin = {{ $origin }};
            var destination = {{ $destination }};

            // Formu oluştur
            var form = document.createElement('form');
            form.action = '/satinal'; // Laravel tarafındaki route'a göre güncelleyin
            form.method = 'post';

            // CSRF token'ı ekleyin (Laravel için gerekli)
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}'; // Blade sayfasında kullanıyorsanız

            // Seçilen koltukları input olarak ekle
            var selectedSeatsInput = document.createElement('input');
            selectedSeatsInput.type = 'hidden';
            selectedSeatsInput.name = 'selectedSeats';
            selectedSeatsInput.value = selectedSeats.join(',');


            // Diğer verileri input olarak ekle
            var travelIdInput = document.createElement('input');
            travelIdInput.type = 'hidden';
            travelIdInput.name = 'travelId'; // Bu alanın ismini controller'da beklediğiniz isimle aynı olmalı
            travelIdInput.value = {{ $travelId }};

            var selectedSeatsAndGendersInput = document.createElement('input');
            selectedSeatsAndGendersInput.type = 'hidden';
            selectedSeatsAndGendersInput.name = 'selectedSeatsAndGenders';
            selectedSeatsAndGendersInput.value = JSON.stringify(selectedSeatsAndGenders);


            var originInput = document.createElement('input');
            originInput.type = 'hidden';
            originInput.name = 'origin';
            originInput.value = {{ $origin }};

            var destinationInput = document.createElement('input');
            destinationInput.type = 'hidden';
            destinationInput.name = 'destination';
            destinationInput.value = {{ $destination }};


            var totalAmountInput = document.createElement('input');
            totalAmountInput.type = 'hidden';
            totalAmountInput.name = 'totalAmount';
            totalAmountInput.value = totalPrice; // totalPrice değişkenini kullan


            // Forma inputları ekle
            form.appendChild(csrfInput);
            form.appendChild(selectedSeatsInput);
            form.appendChild(travelIdInput);
            form.appendChild(originInput);
            form.appendChild(destinationInput);
            // form.appendChild(totalPrice);
            form.appendChild(totalAmountInput);
            // Formu sayfaya ekleyin
            document.body.appendChild(form);
            form.appendChild(selectedSeatsAndGendersInput);

            // console.log(totalAmount);
            console.log(selectedSeats);

            // Formu gönderin
            form.submit();
        }
    </script>
@endsection
