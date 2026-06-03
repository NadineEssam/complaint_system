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
        Schema::table('sfdcomplaints', function (Blueprint $table) {

            if (!Schema::hasColumn('sfdcomplaints', 'created_by')) {
                $table->unsignedBigInteger('created_by')
                    ->nullable()
                    ->after('ComplaintID');
            }

            if (!Schema::hasColumn('sfdcomplaints', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')
                    ->nullable()
                    ->after('created_by');
            }

            if (!Schema::hasColumn('sfdcomplaints', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sfdcomplaints', function (Blueprint $table) {

            if (Schema::hasColumn('sfdcomplaints', 'created_by')) {
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('sfdcomplaints', 'updated_by')) {
                $table->dropColumn('updated_by');
            }

            if (Schema::hasColumn('sfdcomplaints', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};