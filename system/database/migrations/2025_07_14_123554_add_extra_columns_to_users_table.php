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
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->after('name');
            $table->string('phone')->unique()->after('surname');
            $table->string('identity')->unique()->after('phone');
            $table->string('sms_password')->unique()->after('identity');
            $table->boolean('phone_confirmed')->default(false)->after('sms_password');
            $table->enum('gender', ['male', 'female'])->after('identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
               $table->dropColumn(['surname', 'phone', 'identity', 'gender','sms_password','phone_confirmed']);
        });
    }
};
