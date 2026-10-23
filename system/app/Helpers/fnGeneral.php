<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

function convMoney($number, $dec)
{
    if ($number == "") $number = "0";

    $sayi = number_format($number, $dec, ",", ".");

    if ($dec == 0) $sayi = str_replace(",00", "", $sayi);

    return $sayi;
}

function convertToUtf8($text)
{
    if (!$text) return $text;

    $encodings = ['UTF-8', 'ISO-8859-9', 'Windows-1254'];
    $detected = mb_detect_encoding($text, $encodings, true);

    if ($detected && $detected !== 'UTF-8') {
        $text = mb_convert_encoding($text, 'UTF-8', $detected);
    }

    $text = @iconv('UTF-8', 'UTF-8//IGNORE', $text);

    $map = [
        'Ý' => 'İ',
        'Ð' => 'Ğ',
        'Þ' => 'Ş',
        'ý' => 'ı',
        'ð' => 'ğ',
        'þ' => 'ş',
    ];

    return strtr($text, $map);
}

function format_date($date, $format = 'd F Y, l', $lang = null)
{
    if ($lang === null) {
        $lang = App::getLocale(); // 'en', 'tr', 'bg', etc.
    }

    $timestamp = is_numeric($date) ? $date : strtotime($date);

    $translations = [
        'en' => [
            'months' => [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December'
            ],
            'weekdays' => [
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday'
            ]
        ],
        'tr' => [
            'months' => [
                1 => 'Ocak',
                2 => 'Şubat',
                3 => 'Mart',
                4 => 'Nisan',
                5 => 'Mayıs',
                6 => 'Haziran',
                7 => 'Temmuz',
                8 => 'Ağustos',
                9 => 'Eylül',
                10 => 'Ekim',
                11 => 'Kasım',
                12 => 'Aralık'
            ],
            'weekdays' => [
                0 => 'Pazar',
                1 => 'Pazartesi',
                2 => 'Salı',
                3 => 'Çarşamba',
                4 => 'Perşembe',
                5 => 'Cuma',
                6 => 'Cumartesi'
            ]
        ],
        'bg' => [
            'months' => [
                1 => 'Януари',
                2 => 'Февруари',
                3 => 'Март',
                4 => 'Април',
                5 => 'Май',
                6 => 'Юни',
                7 => 'Юли',
                8 => 'Август',
                9 => 'Септември',
                10 => 'Октомври',
                11 => 'Ноември',
                12 => 'Декември'
            ],
            'weekdays' => [
                0 => 'Неделя',
                1 => 'Понеделник',
                2 => 'Вторник',
                3 => 'Сряда',
                4 => 'Четвъртък',
                5 => 'Петък',
                6 => 'Събота'
            ]
        ]
    ];

    $month = (int)date('n', $timestamp);
    $weekday = (int)date('w', $timestamp);

    $formatted = date($format, $timestamp);

    if ($lang !== 'en') {
        $formatted = str_replace($translations['en']['months'], $translations[$lang]['months'], $formatted);
        $formatted = str_replace($translations['en']['weekdays'], $translations[$lang]['weekdays'], $formatted);
    }

    return $formatted;
}


function format_datetime($date, $format = 'd.m.Y')
{
    return $date ? Carbon::parse($date)->format($format) : null;
}

function convAscitoTr($text)
{
    $search = array('Ã¶', 'Ä±', 'Ã§', 'Ä?', 'Å?', 'Ã¼', 'Ãœ', 'ÅŸ', 'ÄŸ', 'Ã‡', 'Ä°', 'Ã–');
    $replace = array('ö', 'ı', 'ç', 'ğ', 'ş', 'ü', 'Ü', 'Ş', 'Ğ', 'Ç', 'İ', 'Ö');
    $new_text = str_replace($search, $replace, $text);
    return $new_text;
}

function convTrtoEng($text)
{
    $text = convAscitoTr(trim($text));
    $search = array('ö', 'Ö', 'ü', 'Ü', 'ç', 'Ç', 'ğ', 'Ğ', 'ş', 'Ş', 'ı', 'İ');
    $replace = array('o', 'O', 'u', 'U', 'c', 'C', 'g', 'G', 's', 'S', 'i', 'I');
    $new_text = str_replace($search, $replace, $text);
    $new_text = strtoupper($new_text);
    return $new_text;
}

function transsmallbig($pString)
{
    $pString = str_replace("ı", "I", $pString);
    $pString = str_replace("i", "İ", $pString);
    $pString = str_replace("ğ", "Ğ", $pString);
    $pString = str_replace("ö", "Ö", $pString);
    $pString = str_replace("ü", "Ü", $pString);
    $pString = str_replace("ç", "Ç", $pString);
    $pString = str_replace("ş", "Ş", $pString);

    $pString = strtoupper($pString);

    return $pString;
}

function sendSmsKobikom($page, $pPhone, $pMessage)
{

    $dontSend = "0";

    if (substr(substr("0" . $pPhone, -10), 0, 1) != "5") {

        $dontSend = "1";
        $smsResult = "0";
        $smsError = "TELEFON NUMASARI HATALI KONTROL EDİNİZ";
    }

    if ($pMessage == "") {

        $dontSend = "1";
        $smsResult = "0";
        $smsError = "MESAJ BOŞ OLAMAZ";
    }

    if ($dontSend == "0") {

        $pMessage = strtoupper(convTrtoEng($pMessage));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://smsportal.kobikom.com.tr:9587/sms/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "type": 1,
            "sendingType": 0,
            "title": "TITLE",
            "content": "' . $pMessage . '",
            "number": "90' . $pPhone . '",
            "encoding": 0,
            "sender": "LUKS YONCA",
            "periodicSettings": null,
            "sendingDate": null,
            "validity": 60,
            "pushSettings": null
        }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic bHVrc3lvbmNhOkZxNm5GdjVxKjU='
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $dzJSonResponse = json_decode($response, true |  JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


        $smsSendId = $dzJSonResponse["data"]["pkgID"] ?? null;
        $smsError = $dzJSonResponse["err"]["message"] ?? null;

        $smsError = convTrtoEng(convAscitoTr($smsError));

        if ($smsError) {
            Log::error("smsError: " . $smsError);
        }

        if (!$smsError and $smsSendId) {
            $smsResult = "1";
        } else {
            $smsResult = "5";
        }

        DB::insert("
            INSERT INTO LUKSYONCA.SMSSEND
            (COMPANYID, PHONE, MESSAGE, INSERTDATE, SENDDATE, STATUS, RESULTDEFINATION, TABLEID, TRANSUSER)
            VALUES
            (:companyId, :phone, :message, NOW(), NOW(), :status, :result, :tableId, :userId)
        ", [
            'companyId' => 1,
            'phone' => $pPhone,
            'message' => strtoupper(convTrtoEng(convAscitoTr($pMessage))),
            'status' => $smsResult,
            'result' => $smsError . ' ' . $smsSendId,
            'tableId' => 0,
            'userId' => "websiteood"
        ]);
    }

    return array($smsResult, $smsError);
}
