<!-- resources/views/welcome.blade.php -->
@extends('site.layouts.app')

@section('title', 'Anasayfa')

@section('css')
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
                <h1>{{ __('messages.my_tickets_title') }}</h1>
            </div>
        </div>
    </div>
</section>

<section class="container mb-2" style="min-height: 50vh">
    <div class="tickets-area-header border shadow justify-items-around p-3 mb-2 d-none d-lg-flex">
        <div class="col-md-2 text-center align-content-center">{{ __('messages.my_tickets_col1') }}</div>
        <div class="col-md-3 text-center align-content-center">{{ __('messages.my_tickets_col2') }}</div>
        <div class="col-md-3 text-center align-content-center">{{ __('messages.my_tickets_col3') }}</div>
        <div class="col-md-2 text-center align-content-center">{{ __('messages.my_tickets_col4') }}</div>
        <div class="col-md-2 text-center align-content-center">{{ __('messages.my_tickets_col5') }}</div>
    </div>

    @foreach ($biletler as $bilet)
    <div class="row border-bottom py-3 align-items-center">
        <div class="col-md-2 text-center py-2 ticket-field d-flex flex-column">
            <span class="d-lg-none">{{ __('messages.my_tickets_col1') }}:</span>
            {{ format_datetime($bilet->LOCATIONDATE, 'd.m.Y H:i') ?? '-' }}
        </div>

        <div class="col-md-3 text-center">
            {{ convertToUtf8($bilet->DEPARTURENAME) }}
            <i class="fas fa-arrow-right mx-2"></i>
            {{ convertToUtf8($bilet->ARRIVALNAME) ?? '-' }}
        </div>

        <div class="col-md-3 text-center py-1 ticket-field d-flex flex-column">
            <span class="d-lg-none">{{ __('messages.my_tickets_col3') }}:</span>
            {{ $bilet->SEAT }} {{ convertToUtf8($bilet->PASSENGERNAME) }}
        </div>

        <div class="col-md-2 text-center py-1 ticket-field d-flex flex-column">
            <span class="d-lg-none">{{ __('messages.my_tickets_col4') }}:</span>
            {{ __('messages.gender_text.' . $bilet->GENDER) ?? '-' }}
        </div>

        <div class="col-md-2 text-center">
            @php
            $definationText = '';
            $SeyahatDurum = $bilet->LOCATIONDATE > date('Y-m-d H:i') ? '1' : '0';

            if ($bilet->STATE == 'S') {
            $definationText = 'Satış';
            }

            if ($SeyahatDurum == '0' and $bilet->STATE == 'S') {
            $definationText = 'Seyahat Gerçekleşti';
            }

            if ($bilet->STATE == 'I') {
            $definationText = 'İptal Bilet';
            }

            //dd($bilet);
            @endphp
            @if ($definationText == 'Satış')

            <button class="btn btn-sm btn-danger cancel-ticket-btn"
                data-url="{{ route('cancelOrder', $bilet->TICKETID) }}">
                {{ __('messages.cancel_button') }}
            </button>

            @else
            {{ $definationText }}
            @endif
        </div>
    </div>
    @endforeach
    <div class="my-3">
        {{ $biletler->links() }}
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade" id="ticketCancelModal" tabindex="-1" aria-labelledby="ticketCancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="ticketCancelModalLabel">{{ __('messages.ticket_cancel_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
                </div>

                <div class="modal-body">
                    {{ __('messages.ticket_cancel_body') }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.ticket_cancel_no') }}</button>
                    <a id="ticketCancelConfirmBtn" href="#"
                        class="btn btn-danger">{{ __('messages.ticket_cancel_yes') }}</a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var cancelUrl = null;

        $('.cancel-ticket-btn').on('click', function() {
            cancelUrl = $(this).data('url');
            $('#ticketCancelModal').modal('show');
        });

        $('#ticketCancelConfirmBtn').on('click', function() {
            if (!cancelUrl) return;

            $.ajax({
                url: cancelUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#ticketCancelModal').modal('hide');

                    alert(response.message);

                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                },
                complete() {
                    $('#ticketCancelModal').modal('hide');
                }
            });
        });
    });
</script>

@endsection