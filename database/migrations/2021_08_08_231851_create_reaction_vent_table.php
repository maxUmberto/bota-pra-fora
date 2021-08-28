<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionVentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reaction_vent', function (Blueprint $table) {
            $table->foreignId('reaction_id')->constrained();
            $table->foreignId('vent_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unique('reaction_id', 'vent_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reaction_vent');
    }
}
