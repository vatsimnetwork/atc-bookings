<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_key_id');
            $table->unsignedBigInteger('cid');
            $table->string('callsign');
            $table->enum('type', ['booking', 'event', 'exam', 'training']);
            $table->timestamp('start')->useCurrent();
            $table->timestamp('end')->useCurrent();
            $table->string('division')->nullable();
            $table->string('subdivision')->nullable();
            $table->timestamps();

            $table->foreign('api_key_id')->references('id')->on('api_keys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function(Blueprint $table) {
            $table->dropForeign('bookings_api_key_id_foreign');
        });
        Schema::dropIfExists('bookings');
    }
}
