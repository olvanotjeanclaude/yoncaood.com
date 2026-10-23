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
        Schema::create('travels', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer("travel_id");
            $table->string('departure', 100);
            $table->string('arrival', 100);
            $table->dateTime('travel_date'); // full datetime: date + time

            $table->unsignedBigInteger('line_id');
            $table->string('route_name');

            $table->text('sub_routes'); //allDepLocation / allLocation merged

            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('salesable_count')->default(0); 

            $table->timestamps();

            $table->unique(['travel_id','travel_date', 'departure', 'arrival'], 'unique_travel_trip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel');
    }
};
