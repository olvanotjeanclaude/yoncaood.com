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
        Schema::create('travel_queries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress('ip'); 
            $table->string('session_id', 64); // Session/device ID
            $table->date('travel_date');
            $table->string('departure', 50);
            $table->string('arrival', 50); 

            $table->timestamps(); 

            $table->index(['session_id', 'departure', 'arrival', 'travel_date'], 'idx_session_route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_queries');
    }
};
