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
        Schema::table('complaint_responses', function (Blueprint $table) {
    $table->timestamp('updated_at')->nullable();
});
    }

    public function down(): void
    {
        Schema::table('complaint_responses', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
