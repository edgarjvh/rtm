<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtherMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('host_email')->nullable(false);
            $table->text('attendees_email')->nullable(false);
            $table->string('host_linkedin')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_meetings');
    }
}
