@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('css')
    <style>
        .is-invalid {
            border-color: #dc3545;
        }

        .text-danger {
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>

@endsection

@section('content')
    <section class="page-header page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>{{ __('messages.passenger_info') }}</h1>
                </div>

            </div>
        </div>
    </section>

    <div id="content">
        <form id="bookingForm" action="{{ route('saveBooking') }}" method="post">
            @csrf
            <section class="container">
                <div class="row">

                    <div class="col-lg-8">
                        @if (!empty($error))
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endif
                        <div class="bg-white shadow-md rounded p-3 confirm-details">
                            <h2 class="text-6 mb-3 mb-sm-4 h2-text">{{ __('messages.passenger_info') }}</h2>

                            <p class="fw-600">{{ __('messages.customer_contact_info') }}</p>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <input class="form-control" name="email" id="email"
                                        placeholder="{{ __('messages.email_placeholder') }}"
                                        value="{{ $contact['email'] ?? '' }}" type="text">
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control" name="mobileNumber" data-bv-field="number"
                                        value="{{ $contact['phone'] ?? '' }}" id="mobileNumber" maxlength="10"
                                        placeholder="{{ __('messages.phone_placeholder') }}" type="text">
                                </div>
                            </div>
                            <p class="text-info">{{ __('messages.ticket_notice') }}</p>
                            <hr
                                style="border: 0; height: 1px; background: linear-gradient(to right, var(--bs-teal), #333, #ccc); margin: 30px 0; opacity: 0.8;">

                            @foreach ($selectedSeatsAndGenders as $index => $seat)
                                <p class="fw-600 koltuk-bg">{{ __('messages.seat_number') }} {{ $seat['seatNo'] }}</p>
                                <div class="row g-3">
                                    <input type="hidden" name="origin" value="{{ $origin }}">
                                    <input type="hidden" name="destination" value="{{ $destination }}">
                                    <input type="hidden" name="travelId" value="{{ $travelId }}">
                                    <input type="hidden" name="amount" value="{{ $totalAmount }}">
                                    <div class="col-sm-3">
                                        <input class="form-control ad" name="pax[{{ $index }}][ad]"
                                            id="isim_{{ $seat['seatNo'] }}"
                                            placeholder="{{ __('messages.first_name_placeholder') }}"
                                            value="{{ $seat['first_name'] ?? '' }}" type="text">
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-control soyad" name="pax[{{ $index }}][soyad]"
                                            id="soyisim_{{ $seat['seatNo'] }}"
                                            placeholder="{{ __('messages.last_name_placeholder') }}"
                                            value="{{ $seat['last_name'] ?? '' }}" type="text">
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="form-select title" style="min-height:52px"
                                            name="pax[{{ $index }}][title]" id="title_{{ $seat['seatNo'] }}">
                                            <option value="Bulgaristan"
                                                {{ isset($seat['nationality']) && $seat['nationality'] === 'Bulgaristan' ? 'selected' : '' }}>
                                                {{ __('messages.bulgaria') }}
                                            </option>
                                            @foreach ($nationalities as $nationality)
                                                <option value="{{ $nationality['nationid'] }}"
                                                    {{ isset($seat['nationality']) && $seat['nationality'] === $nationality['nationid'] ? 'selected' : '' }}>
                                                    {{ $nationality['nationname'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input class="form-control passaportno"
                                            name="pax[{{ $index }}][passaportno]"
                                            id="passaportno_{{ $seat['seatNo'] }}"
                                            placeholder="{{ __('messages.passport_placeholder') }}"
                                            value="{{ $seat['passaportno'] ?? '' }}" type="text">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="hidden" class="form-control" value="{{ $seat['seatNo'] }}"
                                            name="pax[{{ $index }}][seat]" id="seat_{{ $seat['seatNo'] }}"
                                            placeholder="Seat" type="text">
                                    </div>

                                    <div class="col-sm-3">
                                        <input type="hidden" class="form-control" value="{{ $seat['gender'] }}"
                                            name="pax[{{ $index }}][gender]"
                                            id="seat_{{ $seat['gender'] }}_{{ $loop->index ?? '' }}">
                                    </div>

                                </div>
                                <br>
                            @endforeach
                        </div>
                    </div>


                    <aside class="col-lg-4 mt-4 mt-lg-0">
                        <div class="bg-white shadow-md rounded p-3">
                            <h2 class="text-5 mb-3 h2-text">{{ __('messages.payment_info') }}</h2>
                            <hr class="mx-n3">

                            <div class="text-dark bg-light-4 text-4 fw-600 p-3"> {{ __('messages.total') }} <span
                                    class="float-end text-6">{{ $totalAmount }} €</span> </div>
                            <br>

                            <div class="d-grid">
                                <button class="btn btn-primary" type="submit">{{ __('messages.pay_securely') }}</button>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </form>
    </div>

    <br><br>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        function translate(dict) {
            const lang = $('html').attr('lang') || 'tr'; // fallback to 'tr'
            return dict[lang] || dict['tr'];
        }

        $(document).ready(function() {
            const $form = $("#bookingForm");

            $form.validate({
                ignore: ":hidden",
                errorClass: 'is-invalid text-danger',
                validClass: 'is-valid',
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorPlacement: function(error, element) {
                    if (element.is('select')) {
                        error.appendTo(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $form.find('#email').rules("add", {
                required: true,
                email: true,
                messages: {
                    required: translate({
                        tr: "E-posta zorunludur.",
                        bg: "Имейлът е задължителен."
                    }),
                    email: translate({
                        tr: "Geçerli bir e-posta adresi giriniz.",
                        bg: "Моля, въведете валиден имейл адрес."
                    })
                }
            });

            $.validator.addMethod("noLeadingZero", function(value, element) {
                value = value.replace(/\D/g, ''); // remove non-digit chars
                return this.optional(element) || (/^[1-9][0-9]{9}$/).test(value);
            }, translate({
                tr: "Telefon numarası başında 0 olmadan 10 rakam olmalı.",
                bg: "Телефонният номер трябва да съдържа точно 10 цифри без начална 0."
            }));

            $form.find('#mobileNumber').rules("add", {
                required: true,
                minlength: 10,
                maxlength: 10,
                normalizer: function(value) {
                    return value.replace(/\D/g, '');
                },
                noLeadingZero: true,
                messages: {
                    required: translate({
                        tr: "Telefon numarası zorunludur.",
                        bg: "Телефонният номер е задължителен."
                    }),
                    minlength: translate({
                        tr: "Telefon numarası başında 0 olmadan 10 rakam olmalı.",
                        bg: "Телефонният номер трябва да съдържа точно 10 цифри без начална 0."
                    }),
                    maxlength: translate({
                        tr: "Telefon numarası başında 0 olmadan 10 rakam olmalı.",
                        bg: "Телефонният номер трябва да съдържа точно 10 цифри без начална 0."
                    })
                    // No need for a message for noLeadingZero, as it’s already added in the method
                }
            });


            $form.find('.ad').each(function() {
                $(this).rules("add", {
                    required: true,
                    minlength: 2,
                    messages: {
                        required: translate({
                            tr: "Ad zorunludur.",
                            bg: "Името е задължително."
                        }),
                        minlength: translate({
                            tr: "Ad en az {0} karakter olmalı.",
                            bg: "Името трябва да съдържа поне {0} символа."
                        })
                    }
                });
            });

            $form.find('.soyad').each(function() {
                $(this).rules("add", {
                    required: true,
                    minlength: 2,
                    messages: {
                        required: translate({
                            tr: "Soyad zorunludur.",
                            bg: "Фамилното име е задължително."
                        }),
                        minlength: translate({
                            tr: "Soyad en az {0} karakter olmalı.",
                            bg: "Фамилното име трябва да съдържа поне {0} символа."
                        })
                    }
                });
            });

            $form.find('.passaportno').each(function() {
                $(this).rules("add", {
                    required: true,
                    minlength: 4,
                    messages: {
                        required: translate({
                            tr: "Pasaport numarası zorunludur.",
                            bg: "Номерът на паспорта е задължителен."
                        }),
                        minlength: translate({
                            tr: "Pasaport numarası en az {0} karakter olmalı.",
                            bg: "Номерът на паспорта трябва да съдържа поне {0} символа."
                        })
                    }
                });
            });

            $form.find('.title').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: translate({
                            tr: "Uyruk seçimi zorunludur.",
                            bg: "Изборът на националност е задължителен."
                        })
                    }
                });
            });
        });
    </script>

@endsection
