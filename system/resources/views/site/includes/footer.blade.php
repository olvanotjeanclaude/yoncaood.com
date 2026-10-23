<footer id="main-footer" style="background: #edf0f3;">
    <div class="container">
        <div class="row row-wrap">

            <div class="col-md-3">
                <a class="logo" href="index.php">
                    <img class="yonca-logo" src="{{ asset('img/yonca-ood-logo.png') }}" alt="Lüks Yonca Seyahat"
                        title="Lüks Yonca Seyahat">
                </a>
                <div class="notification-phone-area">
                    <p class="text-2">
                        {{ __('messages.footer-cagri') }}
                    </p>
                    <a class="text-5 text-phone" href="tel:+905396267500">
                        <i class="fas fa-phone"></i> 0539 626 75 00
                    </a>
                </div>
            </div>

            <div class="col-md-3 baslik-fk mt-25">
                <h3>LÜKS YONCA OOD</h3>
                <div class="content">
                    <p class="p-1">{{ __('messages.desc_about') }}</p>

                </div>
            </div>


            <div class="col-md-3 baslik-fk">
                <h3>{{ __('messages.quick_access') }}</h3>
                <ul class="list list-footer p-1">
                    <li><a href="/">{{ __('messages.nav_home') }}</a></li>
                    <li><a href="/aboutus">{{ __('messages.nav_about_us') }}</a></li>
                    <li><a href="/offices">{{ __('messages.offices') }}</a></li>
                    <li><a href="/iletisim">{{ __('messages.contact') }}</a></li>
                </ul>
            </div>



            <div class="col-md-3 baslik-fk">
                <h3>{{ __('messages.contact') }}</h3>
                <div>
                    <p class="p-1 mb-0"><i style="font-size:17px;" class="fas fa-map-marker-alt"></i>
                        {{ __('messages.address.value') }}
                    </p>
                    <p class="p-1 mb-0"><a href="tel:+00 359 88 3956100" class="text-color"><i style="font-size:17px;"
                                class="fas fa-phone"></i> {{ __('messages.phone.value') }}</a><br>
                        <a href="tel:+905396267500" class="text-color"><i style="font-size:17px;"
                                class="fas fa-phone"></i> 0539 626 75 00</a>
                    </p>
                    <p class="p-1"><a href="mailto:info@luksyonca.com.tr" class="text-color"><i
                                style="font-size:17px;" class="fas fa-envelope"></i>
                            {{ __('messages.email.value') }}</a></p>
                </div>
                <div>
                    <a class="h4" target="_blank" href="https://www.instagram.com/luksyoncaeurope/"><i
                            class="fab fa-instagram"></i></a><a href="https://www.facebook.com/groups/solak.aida/"
                        target="_blank" class="h4 ms-3"><i class="fab fa-facebook"></i></a>
                </div>
            </div>

            <div class="col-md-10 col-xl-9">

            </div>

        </div>
    </div>

    <div class="copyright">
        <p>CopyRight <a href="https://www.mydata.com.tr" target="_blank">mydata.com.tr</a></p>
    </div>

    <a href="https://wa.me/905396267500" target="_blank" class="whatsapp-link"><img src="/img/whatsapp-icon.png"
            alt="whatsapp icon"></a>
</footer>

<div id="signupModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="registerForm" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('auth.register.sign_up') }}</h5>
                <button type="button" data-modal="signupModal" class="close close-modal" data-dismiss="modal"
                    aria-label="{{ __('auth.register.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="d-block">
                    <label for="phone">{{ __('auth.register.phone_number') }}</label>
                    <input type="phone" class="form-control" name="phone" id="phone"
                        placeholder="{{ __('auth.register.phone_placeholder') }}">
                </div>

                <div class="d-block">
                    <label for="email">{{ __('auth.register.email_address') }}</label>
                    <input type="text" class="form-control" name="email" id="email"
                        placeholder="{{ __('auth.register.email_placeholder') }}">
                </div>

                <div class="d-block">
                    <label for="gender">{{ __('auth.register.gender') }}</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="man">{{ __('auth.register.male') }}</option>
                        <option value="woman">{{ __('auth.register.female') }}</option>
                    </select>
                </div>

                <div class="d-block">
                    <label for="identity">{{ __('auth.register.national_id') }}</label>
                    <input type="text" class="form-control" name="identity" id="identity"
                        placeholder="{{ __('auth.register.national_id_placeholder') }}">
                </div>

                <div class="d-block">
                    <label for="name">{{ __('auth.register.first_name') }}</label>
                    <input type="text" class="form-control" name="name" id="name"
                        placeholder="{{ __('auth.register.first_name_placeholder') }}">
                </div>

                <div class="d-block">
                    <label for="surname">{{ __('auth.register.last_name') }}</label>
                    <input type="text" class="form-control" name="surname" id="surname"
                        placeholder="{{ __('auth.register.last_name_placeholder') }}">
                </div>

                <div class="mt-2">
                    <label for="password">{{ __('auth.register.password') }}</label>
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="{{ __('auth.register.password_placeholder') }}">
                </div>
            </div>
            <div class="mt-2">
                <div id="registerRecaptcha"></div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('auth.register.submit') }}</button>
            </div>
        </form>
    </div>
</div>

<div id="smsValidationModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="smsValidationForm" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('auth.sms_code') }}</h5>
                <button type="button" data-modal="smsValidationModal" class="close close-modal"
                    data-dismiss="modal" aria-label="{{ __('auth.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mt-2">
                    <label for="smsVerificationCode">{{ __('auth.sms_code') }}</label>
                    <input type="text" placeholder="{{ __('auth.sms_code_placeholder') }}" class="form-control"
                        name="smsVerificationCode" id="smsVerificationCode">
                </div>
            </div>

            <div class="modal-footer">
                <button id="submitSmsValidation" class="btn btn-primary">{{ __('auth.verify') }}</button>
            </div>
        </form>
    </div>
</div>


<div id="loginModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="loginForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Oturum Aç</h5>
                    <button type="button" data-modal="loginModal" class="close close-modal" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="loginError" class="text-danger mb-2" style="display: none;"></div>

                    <div class="d-block">
                        <label for="_phone">{{ __('auth.register.phone_number') }}</label>
                        <input type="text" class="form-control" name="phone" id="_phone"
                            placeholder="{{ __('auth.register.phone_placeholder') }}">
                    </div>
                    <div class="mt-2">
                        <label for="_password">{{ __('auth.register.password') }}</label>
                        <input type="password" placeholder="{{ __('auth.register.password_placeholder') }}"
                            class="form-control" name="password" id="_password">
                    </div>
                    <div class="mt-2">
                        <div id="loginRecaptcha"></div>
                    </div>

                    <p>
                        <a href="{{route('password.forget')}}" id="forgot-password-link">
                            {{ __('auth.forgot_password.title') }}
                        </a>
                    </p>

                    <!-- <div id="forgot-password-step1">
                        <input type="text" id="forgot-password-phone" placeholder="{{ __('messages.forgot_password.phone_placeholder', ['example' => '+261 34 12 345 67']) }}">
                        <button id="send-otp-btn">Gönder</button>
                    </div>

                    <div id="forgot-password-step2" style="display:none;">
                        <input type="text" id="forgot-password-otp" placeholder="Doğrulama kodu">
                        <input type="password" id="forgot-password-new" placeholder="Yeni şifre">
                        <button id="reset-password-btn">Şifreyi Sıfırla</button>
                    </div> -->

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">{{ __('auth.login_button') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === 'false') {
            $("#loginModal").modal("show");
        }

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const phone = $('#_phone').val();
            const password = $('#_password').val();

            const recaptcha = grecaptcha.getResponse(loginWidgetId);
            if (recaptcha.length === 0) {
                // alert("Lütfen robot olmadığınızı doğrulayın.");
                // return false;
            }

            $.ajax({
                url: "/login",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: {
                    phone: phone,
                    password: password,
                    'g-recaptcha-response': recaptcha
                },
                success: function(response) {
                    $('#loginError').hide();
                    window.location.href = "/biletlerim";
                },
                error: function(xhr) {
                    $('#loginError').text("Giriş başarısız! Lütfen bilgilerinizi kontrol edin.").show();
                }
            });
        });

        $('#smsValidationForm').on('submit', function(event) {
            event.preventDefault();

            $('#smsVerificationCode').removeClass('is-invalid');
            $('#smsVerificationCode').next('.error-text').remove();

            const formData = {
                phone: $('#smsValidationForm input[name="phone"]').val(),
                smsVerificationCode: $('#smsVerificationCode').val(),
            };

            $.ajax({
                url: '/confirm-phone',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    $('#smsValidationModal').modal('hide');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        if (errors.smsVerificationCode) {
                            $('#smsVerificationCode').addClass('is-invalid');
                            $('<div class="error-text text-danger small mt-1"></div>')
                                .text(errors.smsVerificationCode[0])
                                .insertAfter('#smsVerificationCode');
                        } else if (xhr.responseJSON.message) {
                            $('#smsVerificationCode').addClass('is-invalid');
                            $('<div class="error-text text-danger small mt-1"></div>')
                                .text(xhr.responseJSON.message)
                                .insertAfter('#smsVerificationCode');
                        }
                    } else {
                        alert('Beklenmeyen bir hata oluştu.');
                    }
                    return false
                }
            });
        });

        $('#registerForm').on('submit', function(e) {
            e.preventDefault();

            const recaptcha = grecaptcha.getResponse(registerWidgetId);
            if (recaptcha.length === 0) {
                alert("Lütfen robot olmadığınızı doğrulayın.");
                return false;
            }

            $.ajax({
                url: '/register',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $('#smsValidationModal input[name="phone"]').remove();
                    $('.register-message').remove();

                    const phoneNumber = $('#phone').val();

                    const hiddenPhoneInput = $('<input>').attr('type', 'hidden')
                        .attr('name', 'phone')
                        .val(phoneNumber);

                    $('#smsValidationModal .modal-body').append(hiddenPhoneInput);

                    $('#registerForm')[0].reset();

                    $('#signupModal').modal('hide');
                    $("#smsValidationModal").modal("show");

                    const successMessage = $('<div class="register-message alert alert-success mt-2"></div>').text(response.message);
                    $('#smsValidationModal .modal-body').prepend(successMessage);
                },

                error: function(xhr) {
                    $('.form-control').removeClass('is-invalid');
                    $('.error-text').remove();

                    if (xhr.status === 422) { // validation error
                        let errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            if (errors[key]) {
                                const errorMessages = errors[key];
                                const input = $('#' + key);
                                input.addClass('is-invalid');
                                const errorEl = $('<div class="error-text text-danger small mt-1"></div>').text(errorMessages[0]);
                                errorEl.insertAfter(input);
                            }
                        }
                    } else {
                        console.log('Kayıt başarısız: ' + (xhr.responseJSON.message || 'Bilinmeyen hata'));
                    }

                    return false;
                }

            });
        });

    });
</script>


@endpush