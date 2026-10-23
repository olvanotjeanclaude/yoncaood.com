@extends('site.layouts.app')

@section('title', __('messages.pnr.title'))

@section('content')
    <div class="page-header page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <i class="fas fa-ticket-alt me-2"></i>
                        {{ __('messages.pnr.title') }}
                    </h1>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                        <li><a href="{{ url('/') }}">{{ __('messages.pnr.breadcrumb.home') }}</a></li>
                        <li class="active">{{ __('messages.pnr.breadcrumb.current') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <form id="pnrForm">
                    {{-- Bilgi Kutusu --}}
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-info-circle fa-lg me-3"></i>
                        <div>{!! __('messages.pnr.info_box') !!}</div>
                    </div>

                    {{-- Sorgulama Formu --}}
                    <div class="bg-white shadow-md rounded p-4 mb-4">
                        <h4 class="mb-3 text-primary"><i class="fas fa-search me-2"></i>{{ __('messages.pnr.form.title') }}
                        </h4>
                        <hr class="mb-4">

                        @if (session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <div id="ticketResult"></div>

                        <div class="mb-3">
                            <label for="pnr_code" class="form-label fw-semibold">
                                <i class="fas fa-barcode me-1 text-primary"></i> {{ __('messages.pnr.form.pnr_code') }}
                            </label>
                            <input type="text" class="form-control form-control-lg" id="pnr_code" name="pnr_code"
                                placeholder="{{ __('messages.pnr.form.pnr_placeholder') }}" required>
                            <div class="invalid-feedback" id="pnr_code_error"></div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i> {{ __('messages.pnr.form.phone') }}
                            </label>
                            <input type="tel" class="form-control form-control-lg" id="phone" name="phone"
                                placeholder="{{ __('messages.pnr.form.phone_placeholder') }}" required>
                            <div class="invalid-feedback" id="phone_error"></div>
                        </div>

                        <button class="btn btn-primary btn-lg w-100" id="searchBtn">
                            <span id="btnText"><i
                                    class="fas fa-search me-2"></i>{{ __('messages.pnr.form.submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const validationMessages = {
            pnr_code: {
                required: "{{ __('validation.custom.pnr_code.required') }}",
                minlength: "{{ __('validation.custom.pnr_code.min', ['min' => 5]) }}",
                maxlength: "{{ __('validation.custom.pnr_code.max', ['max' => 10]) }}"
            },
            phone: {
                required: "{{ __('validation.custom.phone.required') }}",
                digits: "{{ __('validation.custom.phone.digits') }}",
                minlength: "{{ __('validation.custom.phone.digits_between', ['min' => 10, 'max' => 11]) }}",
                maxlength: "{{ __('validation.custom.phone.digits_between', ['min' => 10, 'max' => 11]) }}"
            },
        };


        $(document).ready(function() {
            $('#pnrForm').validate({
                rules: {
                    pnr_code: {
                        required: true,
                        minlength: 5,
                        maxlength: 10
                    },
                    phone: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 11
                    }
                },
                messages: validationMessages,
                errorClass: 'is-invalid',
                validClass: 'is-valid',
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                submitHandler: function(form) {
                    $('#ticketResult').html("");

                    const pnrCode = $('#pnr_code').val().trim();
                    const phone = $('#phone').val().trim();
                    const textBtn = $('#btnText').text();

                    $('#btnText').addClass('d-none');

                    $.ajax({
                        url: "{{ route('checkPnr') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            pnr_code: pnrCode,
                            phone: phone,
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                $("<form method='POST' action='/check-pnr'>")
                                    .append(`<input type="hidden" name="_token" value="${$('meta[name=csrf-token]').attr('content')}">`)
                                    .append(`<input type="hidden" name="pnr_code" value="${pnrCode}">`)
                                    .append(`<input type="hidden" name="phone" value="${phone}">`)
                                    .appendTo('body').submit();
                            } else {
                                $('#ticketResult').removeClass('d-none').html(
                                    `<p>${data.message}</p>`);
                            }
                        },
                        error: function({
                            responseJSON: response
                        }) {
                            $('#ticketResult')
                                .removeClass('d-none')
                                .html(`<p class="text-danger">${response?.message}</p>`);
                        },
                        complete: function() {
                            $('#btnText').removeClass('d-none');
                        }
                    });
                }
            });
        });
    </script>
@endsection
