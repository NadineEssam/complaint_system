<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->char('ComplaintGovernorate', 2)
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->integer('ComplaintGovernorate')
                  ->nullable()
                  ->change();
        });
    }
};