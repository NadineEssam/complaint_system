<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->enum('complaint_type', ['internal', 'external'])
                  ->nullable()
                  ->after('RequestType');
        });
    }

    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->dropColumn('complaint_type');
        });
    }
};