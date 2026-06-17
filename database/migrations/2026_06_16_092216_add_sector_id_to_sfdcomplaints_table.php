<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            // $table->unsignedBigInteger('sec_id')->nullable()->after('StatusDetails');

            // لو عندك جدول sectors وعايز foreign key
            $table->unsignedBigInteger('sec_id')->nullable()->after('StatusDetails');
        });

    }

    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {
            $table->dropForeign(['sec_id']);
            $table->dropColumn('sec_id');
        });
    }
};
