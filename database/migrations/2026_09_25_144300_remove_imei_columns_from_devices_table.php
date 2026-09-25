<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropUnique(['imei_1']); 
            $table->dropUnique(['imei_2']);

            $table->dropColumn([ 'imei_1', 'imei_2', ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('imei_1', 15)->unique(); 
            $table->string('imei_2', 15)->unique();
        });
    }
};
