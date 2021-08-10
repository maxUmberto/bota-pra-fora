<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vent_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vent_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('view_location_lat', 10, 2)->nullable();
            $table->decimal('view_location_lon', 11, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vent_views');
    }
}
