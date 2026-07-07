<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->char('ComplaintProjectType', 2)
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->integer('ComplaintProjectType')
                  ->change();
        });
    }
};