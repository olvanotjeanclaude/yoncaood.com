<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Bilet Detayları</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">
    <div
        style="width: 90vw; max-width: 760px; margin: auto; background-color: #fff; font-family: Arial, sans-serif; font-size: 16px; color: #333; padding: 18px; border: 2px dashed #ccc; border-radius: 16px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 24px;     background-color: #e9f2eb;     color: #00a651; height: 60px; border-radius: 16px 16px 0 0;">
            <h2 style="margin: 0; font-size: 20px; display: flex; align-items: center; gap: 8px;">
                <img src="{{ asset('img/ticket.png') }}" alt="Ticket" style="height: 26px; position: relative; top: -1px;">
                Bilet Detayları
            </h2>
            <a class="logo" href="index.php" style="display: inline-block; height: 40px;">
                <img class="yonca-logo" src="{{ asset('img/yonca-ood-logo.png') }}" alt="Lüks Yonca Seyahat" title="Lüks Yonca Seyahat" style="height: 40px; object-fit: contain;">
            </a>
        </div>
        <div style="padding: 8px; border: 1px solid #e9f2eb;">

            <p>Aşağıda, sistemimizde kayıtlı bilet bilgilerini bulabilirsiniz.</p>


            @if (isset($passengers) && count($passengers))
            @php
            $origin = $passengers[0]->originLocation;
            $destination = $passengers[0]->destinationLocation;
            $totalPrice = $passengers->sum('price');
            @endphp

            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 15px; font-size: 14px;">
                <div style="flex: 1 1 45%; background-color: #f0f7f3; padding: 10px 14px; border-radius: 5px; box-shadow: 0 1px 4px rgba(0, 166, 81, 0.15);">
                    <strong style="color: #008a39;">Güzergah:</strong> {{ $origin->name ?? '-' }} → {{ $destination->name ?? '-' }}
                </div>
                <div style="flex: 1 1 45%; background-color: #f0f7f3; padding: 10px 14px; border-radius: 5px; box-shadow: 0 1px 4px rgba(0, 166, 81, 0.15);">
                    <strong style="color: #008a39;">Telefon:</strong> {{ $passengers[0]->contact->mobile_number ?? 'Yok' }}
                </div>
                <div style="flex: 1 1 45%; background-color: #f0f7f3; padding: 10px 14px; border-radius: 5px; box-shadow: 0 1px 4px rgba(0, 166, 81, 0.15);">
                    <strong style="color: #008a39;">E-posta:</strong> {{ $passengers[0]->contact->email ?? 'Yok' }}
                </div>
                <div style="flex: 1 1 45%; background-color: #f0f7f3; padding: 10px 14px; border-radius: 5px; box-shadow: 0 1px 4px rgba(0, 166, 81, 0.15);">
                    <strong style="color: #008a39;">Toplam Fiyat:</strong> {{ number_format($totalPrice, 2) }} EUR
                </div>
            </div>


            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background-color:#50555a; color: white;">
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">#</th>
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">İsim</th>
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">Pasaport</th>
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">Fiyat</th>
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">Koltuk No</th>
                        <th style="padding: 8px; border: 1px solid #ddd; font-weight:500;">Cinsiyet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($passengers as $index => $passenger)
                    <tr style="font-size:15px;">
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $index + 1 }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $passenger->fullname }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $passenger->passaportno }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $passenger->price }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $passenger->seat }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            {{ $passenger->gender == 'woman' ? 'Kadın' : 'Erkek' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color: #999;">Kayıtlı bilet bilgisi bulunamadı.</p>
            @endif

            <p style="margin-top: 30px; font-size: 13px; color: #666;">
                Bu e-posta otomatik olarak oluşturulmuştur. Lütfen yanıtlamayınız.
            </p>
        </div>
    </div>
</body>

</html>