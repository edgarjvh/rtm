<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserOutlookTokenCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('outlook_account')->nullable(true)->default(null);
            $table->string('outlook_access_token')->nullable(true)->default(null);
            $table->string('outlook_refresh_token')->nullable(true)->default(null);
            $table->string('outlook_avatar')->nullable(true)->default(null);
            $table->string('outlook_id')->nullable(true)->default(null);
            $table->bigInteger('outlook_expiry_token')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
