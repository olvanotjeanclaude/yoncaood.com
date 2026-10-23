@extends('site.layouts.app')

@section('title', 'Bilet Bilgileri')

@section('content')

    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header page-header-dark bg-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Bilet Bilgileri</h1>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb justify-content-start justify-content-md-end mb-0">
                        <li><a href="{{ url('/') }}">Anasayfa</a></li>
                        <li class="active">Bilet Bilgileri</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-5" style="min-height: 60vh;">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h2 class="mb-3 text-detay"> <img src="{{ asset('img/ticket.png') }}" alt="Ticket" class="ticket-icon">
                    Bilet Detayları</h2>
                <p class="text-muted fs-16 mb-4">Aşağıda sistemimizde kayıtlı bilet bilgilerini bulabilirsiniz.</p>

                @if (isset($passengers) && count($passengers))
                    @php
                        $origin = $passengers[0]->originLocation;
                        $destination = $passengers[0]->destinationLocation;
                        $totalPrice = $passengers->sum('price');
                    @endphp

                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded text-center shadow-sm">
                                <div class="fw-semibold text-secondary fs-17 mb-1">Güzergah</div>
                                <div class="fs-17">{{ $origin->name ?? '-' }} → {{ $destination->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded text-center shadow-sm">
                                <div class="fw-semibold text-secondary fs-17 mb-1">Tarih</div>
                                <div class="fs-17">
                                    {{ \Carbon\Carbon::parse($passengers[0]->travel_date)->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded text-center shadow-sm">
                                <div class="fw-semibold text-secondary fs-17 mb-1">Telefon</div>
                                <div class="fs-17">{{ $passengers[0]->contact->mobile_number ?? 'Yok' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded text-center shadow-sm">
                                <div class="fw-semibold text-secondary fs-17 mb-1">E-posta</div>
                                <div class="fs-17">{{ $passengers[0]->contact->email ?? 'Yok' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light p-3 rounded text-center shadow-sm">
                                <div class="fw-semibold text-secondary fs-17 mb-1">Toplam Fiyat</div>
                                <div class="fs-17">{{ number_format($totalPrice, 2) }} BGN</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center shadow-sm fs-15">
                            <thead class="table-green">
                                <tr>
                                    <th>#</th>
                                    <th>İsim</th>
                                    <th>Pasaport</th>
                                    <th>Koltuk No</th>
                                    <th>Cinsiyet</th>
                                    <th>Tutar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($passengers as $index => $passenger)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $passenger->fullname }}</td>
                                        <td>{{ $passenger->passaportno }}</td>
                                        <td><span class="badge bdg-style bg-primary">{{ $passenger->seat }}</span></td>
                                        <td>
                                            {{ $passenger->gender == 'woman' ? 'Kadın' : 'Erkek' }}
                                        </td>
                                        <td>{{ number_format($passenger->price ?? 0, 2) }} BGN</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mt-4">
                        Kayıtlı bilet bilgisi bulunamadı.
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection
