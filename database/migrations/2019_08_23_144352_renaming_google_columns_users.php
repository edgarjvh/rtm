<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamingGoogleColumnsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('access_token', 'google_access_token');
            $table->renameColumn('refresh_token', 'google_refresh_token');
            $table->renameColumn('expiry', 'google_expiry_token');
            $table->renameColumn('avatar', 'google_avatar');
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
