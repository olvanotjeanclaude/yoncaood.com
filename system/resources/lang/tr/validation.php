<?php

return [
    'required' => ':attribute alanı zorunludur.',
    'unique' => ':attribute zaten kayıtlıdır.',
    'email' => ':attribute geçerli bir email adresi olmalıdır.',
    'min' => [
        'string' => ':attribute en az :min karakter olmalıdır.',
    ],
    'digits' => ':attribute alanı :digits haneli olmalıdır.',
    'sms_code_invalid' => 'SMS doğrulama kodu geçersiz.',
    'phone_confirmed' => 'Telefon numarası başarıyla onaylandı.',
    'regex' => 'Girdiğiniz :attribute geçerli bir formatta değil.',
    'numeric' => ':attribute alanı sayısal olmalıdır.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'attributes' => [
        'phone' => 'telefon numarası',
        'email' => 'email adresi',
        'name' => 'ad',
        'surname' => 'soyad',
        'gender' => 'cinsiyet',
        'identity' => 'TC kimlik numarası',
        'password' => 'şifre',
        'identity' => 'TC kimlik numarası',
        'smsVerificationCode' => 'SMS doğrulama kodu',
        'password_confirmation' => 'şifre doğrulaması',
    ],
    'custom' => [
        'phone' => [
            'regex' => 'Lütfen başında 0 olmadan geçerli bir Türk cep telefonu numarası giriniz (örnek: 5XXXXXXXXX).',
            'required' => 'Telefon numarası zorunludur.',
            'digits' => 'Telefon numarası sadece rakamlardan oluşmalıdır.',
            'digits_between' => 'Telefon numarası :min ile :max haneli olmalıdır.',
        ],
        'identity' => [
            'numeric' => 'TC kimlik numarası sadece rakamlardan oluşmalıdır.',
        ],
        'pnr_code' => [
            'required' => 'PNR kodu zorunludur.',
            'min' => 'PNR en az :min karakter olmalıdır.',
            'max' => 'PNR en fazla :max karakter olabilir.',
        ],
    ],
    'confirmed' => ':attribute alanı ile eşleşmiyor.',
];
