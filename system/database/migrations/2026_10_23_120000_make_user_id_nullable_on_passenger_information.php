<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Guest bookings have no matching user, so user_id must allow null.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE passenger_information MODIFY user_id INT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('UPDATE passenger_information SET user_id = 0 WHERE user_id IS NULL');
        DB::statement('ALTER TABLE passenger_information MODIFY user_id INT UNSIGNED NOT NULL');
    }
};
