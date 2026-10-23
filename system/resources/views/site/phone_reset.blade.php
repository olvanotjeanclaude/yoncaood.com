@extends('site.layouts.app')

@section('title', __('auth.forgot_password.reset_title'))

@section('content')
<div id="content">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light text-center py-3">
                        <h4 class="mb-0">{{ __('auth.forgot_password.reset_title') }}</h4>
                    </div>
                    <div class="card-body">

                        <!-- Display errors -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" id="phoneResetForm" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="phone" value="{{ $phone }}">

                            <div id="resetPasswordMsg" class="mb-3"></div>


                            <div class="mb-3">
                                <label for="code">{{ __('auth.forgot_password.code') }}</label>
                                <input type="text" id="code" name="code" class="form-control" maxlength="6"
                                    placeholder="{{ __('auth.forgot_password.code_placeholder') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password">{{ __('auth.forgot_password.new_password') }}</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label
                                    for="password_confirmation">{{ __('auth.forgot_password.confirm_password') }}</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2">
                                {{ __('auth.forgot_password.reset_button') }}
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const lang = @json(__('auth.forgot_password'));

        // jQuery Validate setup
        $("#phoneResetForm").validate({
            rules: {
                code: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                password: {
                    required: true,
                    minlength: 6
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                code: {
                    required: lang.code_required,
                    digits: lang.code_digits ,
                    minlength: lang.code_min,
                    maxlength: lang.code_max
                },
                password: {
                    required: lang.password_required,
                    minlength: lang.password_min
                },
                password_confirmation: {
                    required: lang.password_confirmation_required,
                    equalTo: lang.password_confirmation_match
                }
            },
            errorClass: 'is-invalid',
            errorElement: 'div',
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                const $form = $(form);

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            $("#resetPasswordMsg").html(`<div class="text-success">${ res.message}</div>`);
                            window.location.href = res.redirect_url;
                        } else {
                            $("#resetPasswordMsg").html(`<div class="text-danger">${res.message}</div>`);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors || {};
                        let messages = '';
                       
                        $.each(errors, function(key, value) {
                            messages += `<div class="text-danger">${value[0]}</div>`;
                        });
                        
                        $("#resetPasswordMsg").html(messages);
                    }
                });
            }
        });
    });
</script>
@endsection