<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
        Schema::create('complaint_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('complaint_id');
            $table->unsignedInteger('comsource_id');

            $table->foreign('complaint_id')
                  ->references('ComplaintID')
                  ->on('sfdcomplaints')
                  ->onDelete('cascade');

            $table->foreign('comsource_id')
                  ->references('comsourcesid')
                  ->on('comsources')
                  ->onDelete('cascade');

            $table->unique(['complaint_id', 'comsource_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('complaint_sources');
    }
};