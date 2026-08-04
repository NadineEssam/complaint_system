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
    Schema::table('comsources', function (Blueprint $table) {
        $table->tinyInteger('validity')->default(1)->after('comsourcesname');
    });
}

public function down(): void
{
    Schema::table('comsources', function (Blueprint $table) {
        $table->dropColumn('validity');
    });
}
};



