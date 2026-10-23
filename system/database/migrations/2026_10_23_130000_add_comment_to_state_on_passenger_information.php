<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Document the state codes on the column itself.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE passenger_information MODIFY state ENUM('B','S','I','H') NOT NULL DEFAULT 'B' COMMENT 'B=Beklemede (ödeme bekleniyor), S=Satış (ödendi, bilet kesildi), I=İptal/İade (detay state_definition alanında), H=Hata (ödeme/satış hatası)'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE passenger_information MODIFY state ENUM('B','S','I','H') NOT NULL DEFAULT 'B'");
    }
};
