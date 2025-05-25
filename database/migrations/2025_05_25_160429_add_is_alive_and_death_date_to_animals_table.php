<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->boolean('is_alive')->default(true)->after('is_vaccinated');
            $table->date('death_date')->nullable()->after('is_alive');
        });
    }

    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn(['is_alive', 'death_date']);
        });
    }
};