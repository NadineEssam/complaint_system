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
    Schema::table('comp_close_reason_classify', function (Blueprint $table) {
        $table->tinyInteger('validity')->default(1)->after('close_reason_classify_Name');
    });
}

public function down(): void
{
    Schema::table('comp_close_reason_classify', function (Blueprint $table) {
        $table->dropColumn('validity');
    });
}
};
