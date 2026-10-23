<?php

namespace App\Services\Mydata;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Travel;

class MydataService
{
    protected string $baseUrl = 'https://luksyonca.mydata.com.tr/vts';
    protected string $userId = 'luksyoncaood';
    protected string $password = 'JsE7Ozw59d3Q';

    /**
     * Fetch nationality list.
     */

    public function getNationalities(): array
    {
        $response = Http::get("{$this->baseUrl}/nationality.php", [
            'UserId'   => $this->userId,
            'Password' => $this->password,
        ]);


        if (! $response->successful()) {
            return [];
        }

        $parsed = $this->parseXmlResponse($response->body());

        return $parsed['nationalitys']['nation'] ?? [];
    }

    /**
     * Fetch travel data with specific query.
     */
    public function getTravels(string $departure, string $arrival, string $firstDate, ?string $lastDate = null)
    {
        $lastDate = $lastDate ?? $firstDate;

        $query = [
            'UserId'     => $this->userId,
            'Password'   => $this->password,
            'departure'  => $departure,
            'arrival'    => $arrival,
            'firstDate'  => $firstDate,
            'lastDate'   => $lastDate,
        ];

     
        $response = Http::get("{$this->baseUrl}/travel.php", $query);

        $xmlData = simplexml_load_string($response->body());

        // header('Content-Type: application/xml');
        // echo $response->body();exit;
        
        $responses = $this->parseXmlResponse($response->body());
        $responses = $responses["travels"]["travel"] ?? [];

        $travels = $xmlData->travels->travel ?? [];


        foreach ($travels as $item) {
            $item = (array)$item;

            Travel::updateOrCreate(
                [
                    'departure' => $departure,
                    'arrival' => $arrival,
                    'travel_date' => $item['locationdate'],
                    'travel_id' => $item['travelid'],
                ],
                [
                    'line_id' => (int)$item['lineid'],
                    'route_name' => $item['routename'],
                    'sub_routes' => $item['allLocation'],
                    'is_active' => $item['aktive'] === '1' ? 1 : 0,
                    'salesable_count' => (int)$item['salesableCount'],
                ]
            );
        }

        return $travels;
    }

    /**
     * Fetch seat layout for a given travel ID.
     */
    public function getSeatLayout(string $travelId, string $departure, string $arrival): array
    {
        $response = Http::get("{$this->baseUrl}/seat.php", [
            'UserId'   => $this->userId,
            'Password' => $this->password,
            'travelId' => $travelId,
            'departure' => $departure,
            'arrival'  => $arrival,
        ]);

        if (! $response->successful()) {
            return [
                'error' => true,
                'message' => 'Koltuk düzeni getirme hatası!',
            ];
        }

        //  header('Content-Type: application/xml');
        // echo $response->body();exit;

        return $this->parseXmlResponse($response->body());
    }

    public function makeSale(array $params)
    {
        $query = array_merge([
            'UserId'   => $this->userId,
            'Password' => $this->password,
        ], $params);

        $response = Http::get("{$this->baseUrl}/sales.php", $query);

        return $this->parseXmlResponse($response->body());
    }

    public function ticketState(array $params)
    {
        $query = array_merge([
            'UserId'   => $this->userId,
            'Password' => $this->password,
        ], $params);

        $response = Http::get("{$this->baseUrl}/ticketState.php", $query);

        return $this->parseXmlResponse($response->body());
    }

    public function cancelTicket(array $params)
    {
        $query = array_merge([
            'UserId'   => $this->userId,
            'Password' => $this->password,
        ], $params);

        $response = Http::get("{$this->baseUrl}/cancel.php", $query);

        return $this->parseXmlResponse($response->body());
    }


    /**
     * Common XML parsing logic to convert to array.
     */
    private function parseXmlResponse(string $xmlContent): array
    {
        $rawXml = mb_convert_encoding($xmlContent, 'UTF-8', 'ISO-8859-9');

        $xml = simplexml_load_string($rawXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            return [];
        }

        $response = json_decode(json_encode($xml), true);

        return $this->utf8_decode_recursive($response);
    }

    public function getExchangeRate(string $currencyCode = 'BGN'): ?float
    {
        $response = Http::get('https://www.tcmb.gov.tr/kurlar/today.xml');

        if (! $response->successful()) {
            return null;
        }

        $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($xml), true);

        foreach ($data['Currency'] as $currency) {
            if ($currency['@attributes']['CurrencyCode'] === $currencyCode) {
                $price = $currency['ForexSelling'];

                return floor($price);
            }
        }

        return null;
    }

    private function utf8_decode_recursive($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'utf8_decode_recursive'], $data);
        } elseif (is_string($data)) {
            return utf8_decode($data);
        }
        return $data;
    }
}
