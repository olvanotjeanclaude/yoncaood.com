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
        Schema::create('send_sms', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->nullable()->index();
            $table->string('phone', 20)->index();
            $table->text('message');
            $table->timestamp('send_date')->nullable()->index();
            $table->string('status', 50)->nullable()->index();
            $table->text('result_definition')->nullable();
            $table->string('trans_user')->nullable();
            $table->timestamps(); // adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_sms');
    }
};
