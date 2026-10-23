@extends('site.layouts.app')

@section('title', __('messages.pnr.title'))

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --brand-green: #00A651;
            --brand-green-rgba: rgba(0, 166, 81, 0.1);
            --brand-green-dark: rgba(0, 166, 81, 0.9);
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ticket-header {
            background: linear-gradient(135deg, var(--brand-green) 0%, #008542 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 166, 81, 0.2);
        }

        .ticket-body {
            background: white;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 30px;
        }

        .travel-line {
            border-top: 2px dashed rgba(255, 255, 255, 0.3);
            position: relative;
            margin: 12px 0;
        }

        .bus-icon {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: var(--brand-green);
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 0.9rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .label-text {
            text-transform: uppercase;
            font-size: 0.7rem;
            color: rgba(0, 0, 0, 0.4);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .pax-row {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .pax-row:last-of-type {
            border-bottom: none;
        }

        .gender-badge {
            background-color: var(--brand-green-rgba);
            color: var(--brand-green);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 8px;
        }

        .insurance-alert {
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #9b7a00;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .total-price {
            color: var(--brand-green);
            font-weight: 800;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5" style="max-width: 650px;">
        <div class="ticket-header">
            <div class="row align-items-center">
                <div class="col-7">
                    <div class="label-text text-white-50">{{ __('messages.departure_date') }}</div>
                    {{-- <div class="h5 mb-0 fw-bold">{{format_date($ticket->LOCATIONDATE) $ticket->DEPARTUREDATE }}</div> --}}
                    <div class="h5 mb-0 fw-bold">{{ format_date($ticket->DEPARTUREDATE) }}</div>
                </div>
                <div class="col-5 text-end">
                    <div class="label-text text-white-50">{{ __('messages.departure_time') }}</div>
                    <div class="display-6 mb-0 fw-bold">{{ $ticket->DEPARTURETIME }}</div>
                </div>
            </div>

            <div class="row mt-4 align-items-center">
                <div class="col-4">
                    <div class="h4 mb-0 fw-bold">{{ convertToUtf8($ticket->DEPARTURENAME) }}</div>
                    <div class="label-text text-white-50">{{ __('messages.departure_city') }}</div>
                </div>
                <div class="col-4 text-center">
                    <div class="travel-line">
                        <span class="bus-icon"><i class="bi bi-bus-front-fill"></i></span>
                    </div>
                </div>
                <div class="col-4 text-end">
                    <div class="h4 mb-0 fw-bold">{{ convertToUtf8($ticket->ARRIVALNAME) }}</div>
                    <div class="label-text text-white-50">{{ __('messages.arrival_city') }}</div>
                </div>
            </div>
        </div>

        <div class="ticket-body">

            <div class="pax-row">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-sm-5">
                        <div class="label-text mb-1">{{ __('messages.passenger_name') }}</div>
                        <div class="fw-bold">{{ convertToUtf8($ticket->PASSENGERNAME) }}</div>
                    </div>
                    <div class="col col-sm">
                        <div class="label-text mb-1">{{ __('messages.seat_number') }}</div>
                        <div class="h5 mb-0 fw-bold" style="color: var(--brand-green);">{{ $ticket->SEAT }}</div>
                    </div>
                    <div class="col col-sm">
                        <div class="label-text mb-1">{{ __('messages.passenger_gender') }}</div>
                        <span
                            class="gender-badge">{{ $ticket->GENDER == 'man' ? __('messages.male') : __('messages.female') }}</span>
                    </div>

                    @php
                        switch ($ticket->STATE_LABEL) {
                            case 'cancelled':
                                $badgeClass = 'text-danger';
                                break;
                            case 'sold':
                                $badgeClass = 'text-success';
                                break;
                            case 'completed':
                                $badgeClass = 'text-primary';
                                break;
                            default:
                                $badgeClass = 'text-secondary';
                        }
                    @endphp

                    <div class="col col-sm">
                        <div class="label-text mb-1">{{ __('messages.ticket_status') }}</div>
                        <span class="status-badge {{ $badgeClass }}">
                            {{ __('messages.ticket_statuses.' . $ticket->STATE_LABEL) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- <div class="pax-row">
                <div class="row g-3 align-items-center">
                    <div class="col-6">
                        <div class="label-text mb-1">Yolcu Adı</div>
                        <div class="fw-bold">AYŞE YILMAZ</div>
                    </div>
                    <div class="col-3 text-center">
                        <div class="label-text mb-1">Koltuk</div>
                        <div class="h5 mb-0 fw-bold" style="color: var(--brand-green);">17</div>
                    </div>
                    <div class="col-3 text-end">
                        <div class="label-text mb-1">Cinsiyet</div>
                        <span class="gender-badge"
                            style="background-color: rgba(233, 30, 99, 0.1); color: #e91e63;">Kadın</span>
                    </div>
                </div>
            </div> --}}

            @if ($ticket->PRICE)
                <div class="mt-4 pt-4 border-top">
                    {{-- <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary small">Bilet Fiyatı (2 Kişi)</span>
                    <span class="fw-bold">3.700,00 ₺</span>
                </div> --}}

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">{{ __('messages.total_amount') }}</h5>
                        <h4 class="total-price mb-0">{{ convMoney($ticket->PRICE, 2) }} {{$ticket->CURRENCY}}</h4>
                    </div>
                </div>
            @endif
        </div>

        {{-- <div class="text-center">
            <button class="btn btn-outline-secondary btn-sm px-4">
                <i class="bi bi-printer me-2"></i>Bileti Yazdır
            </button>
        </div> --}}
    </div>
@endsection
