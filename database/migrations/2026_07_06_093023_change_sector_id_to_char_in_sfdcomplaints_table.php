<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->char('sector_id', 2)
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->bigInteger('sector_id')
                  ->nullable()
                  ->change();
        });
    }
};