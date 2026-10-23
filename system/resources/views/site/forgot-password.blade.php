@extends('site.layouts.app')

@section('title', __('auth.forgot_password.title'))

@section('content')
<div id="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">

                <!-- Card wrapper -->
                <div class="card shadow-sm border-0">

                    <!-- Header -->
                    <div class="card-header bg-light border-bottom text-center py-3">
                        <h4 class="mb-0 fw-normal text-secondary">{{ __('auth.forgot_password.title') }}</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body px-4 py-4">

                        <!-- Message container -->
                        <div id="forgotPasswordMsg" class="mb-3"></div>

                        <!-- Form -->
                        <form id="forgotPasswordForm">

                            <!-- Phone field -->
                            <div class="row mb-3 align-items-center">
                                <label for="phone" class="col-sm-4 col-form-label text-start">
                                    {{ __('auth.forgot_password.phone_label') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-8">
                                    <input type="tel" id="phone" name="phone"
                                        class="form-control border-success"
                                        placeholder="{{ __('auth.forgot_password.phone_placeholder') }}" required />
                                </div>
                            </div>

                            <!--  first_letter field -->
                            <div class="row mb-4 align-items-center">
                                <label for="first_letter" class="col-sm-4 col-form-label text-start">
                                    {{ __('auth.forgot_password.first_letter_label') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" id="first_letter" name="first_letter"
                                        class="form-control border-success" maxlength="1"
                                        placeholder="{{ __('auth.forgot_password.first_letter_placeholder') }}" />
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4">
                                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold fs-6">
                                        {{ __('auth.forgot_password.submit_button') }}
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div><!-- /card-body -->
                </div><!-- /card -->

            </div>
        </div>
    </div>
</div>

<br><br>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const lang = @json(__('auth.forgot_password'));

        $("#forgotPasswordForm").validate({
            rules: {
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                first_letter: {
                    required: true,
                    minlength: 1,
                    maxlength: 1
                }
            },
            messages: {
                phone: {
                    required: lang.phone_required,
                    digits: lang.phone_digits,
                    minlength: lang.phone_min,
                    maxlength: lang.phone_max
                },
                first_letter: {
                    required: lang.phone_required,
                    digits: lang.phone_digits,
                    minlength: lang.phone_min,
                    maxlength: lang.phone_max
                },
            },
            errorClass: 'is-invalid',
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                $.ajax({
                    url: "{{ route('password.check_phone') }}",
                    type: 'POST',
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            $("#forgotPasswordMsg").html(`<div class="text-success">${res.message}</div>`);
                            window.location.href = res.reset_url;
                        } else {
                            $("#forgotPasswordMsg").html(`<div class="text-danger">${res.message}</div>`);
                        }
                    },
                    error: function() {
                        $("#forgotPasswordMsg").html('<div class="text-danger">Beklenmedik hata oluştu</div>');
                    }
                });
            }
        });
    });
</script>
@endsection