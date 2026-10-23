@extends('site.layouts.app')

@section('title')
    Bilgilerim
@endsection

@section('content')
    <section class="page-header-1 page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>{{ __('messages.my_infos') }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-4">
        <div class="row">
            <div class="col-md-7">
                <form id="frmUserInfo">
                    @csrf
                    <div class="border shadow d-flex flex-wrap py-5 px-md-2">
                        <div class="col-12 pb-4 ps-4">
                            <span class="h3">{{ __('messages.my_info_header') }}</span>
                        </div>
                        <div class="col-6 px-3"><label for="phone">{{ __('messages.info_phone_number') }}</label><input
                                type="text" class="form-control" name="phone" id="phone"
                                value="{{ $user->phone }}"></div>
                        <div class="col-6 px-3"><label for="identity">{{ __('messages.info_tc_no') }}</label><input
                                type="text" class="form-control" name="identity" id="identity"
                                value="{{ $user->identity }}"></div>
                        <div class="col-6 px-3"><label for="name">{{ __('messages.info_name') }}</label><input
                                type="text" class="form-control" name="name" id="name"
                                value="{{ $user->name }}"></div>
                        <div class="col-6 px-3"><label for="surname">{{ __('messages.info_surname') }}</label><input
                                type="text" class="form-control" name="surname" id="surname"
                                value="{{ $user->surname }}"></div>
                        <div class="col-6 px-3"><label for="email">{{ __('messages.info_email') }}</label><input
                                type="text" class="form-control" name="email" id="email"
                                value="{{ $user->email }}"></div>


                        <div class="col-12 px-3 pt-4 d-flex">
                            <button type="button" id="saveInfos"
                                class="btn btn-success mx-auto">{{ __('messages.info_submit_text') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <form method="POST" action="/update-password" id="changePasswordForm">
                    @csrf
                    <div class="border shadow d-flex flex-column flex-wrap py-5 px-md-2">
                        <div class="px-3">
                            <center>
                                @if (session('success'))
                                    <div class="alert alert-success  alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                            </center>
                            <h5 class="card-title mb-3">{{ __('auth.change_password_title') }}</h5>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">{{ __('auth.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="new_password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('auth.confirm_password') }}</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.update_password_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#saveInfos').on('click', function() {
            clearFormErrors("#frmUserInfo");
            $('#frmUserInfo .alert').remove();

            $.ajax({
                'url': '/update-profile',
                'type': 'POST',
                'data': $('#frmUserInfo').serialize(),
                success: function(response) {
                    const alertHtml = `
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            ${response.message}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    `;

                    // Prepend alert inside the form
                    $('#frmUserInfo').prepend(alertHtml);
                },

                error: function(xhr) {
                    handleAjaxError(xhr, "#frmUserInfo");

                }
            });

        });
    </script>
@endsection
