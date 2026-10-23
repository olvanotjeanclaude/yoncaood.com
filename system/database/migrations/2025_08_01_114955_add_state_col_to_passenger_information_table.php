<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('passenger_information', function (Blueprint $table) {
            $table->enum('state', ['B', 'S', 'I', 'C', 'H'])
                ->default('B')
                ->after('bank_order_id')
                ->comment('B=Beklemede, S=Satış, I=İade, C=İptal, H=Hata (Ödeme Hatası)');

            $table->string('state_definition')
                ->nullable()
                ->after('state')
                ->comment('Durum açıklaması');

            $table->string('pnr_number')
                ->nullable()
                ->after('state_definition')
                ->comment('PNR numarası');

            $table->json('bank_response')
                ->nullable()
                ->after('pnr_number')
                ->comment('Banka API cevabı');

            $table->json('sale_response')
                ->nullable()
                ->after('bank_response')
                ->comment('Satış servisi cevabı');
        });
    }

    public function down()
    {
        Schema::table('passenger_information', function (Blueprint $table) {
            $table->dropColumn([
                'state',
                'state_definition',
                'pnr_number',
                'bank_response',
                'sale_response',
            ]);
        });
    }
};
