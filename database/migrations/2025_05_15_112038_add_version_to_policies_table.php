<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->integer('version')->nullable()->after('type'); // Adjust position if needed
        });
    }
    
    public function down()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('version');
        });
    }
    
};
