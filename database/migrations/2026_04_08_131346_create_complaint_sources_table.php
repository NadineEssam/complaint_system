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
        Schema::create('complaint_sources', function (Blueprint $table) {
            $table->id(); // primary key for this table

            // Pivot columns
            $table->integer('complaint_id'); // must match sfdcomplaints.ComplaintID type
            $table->integer('comsource_id'); // must match comsources.comsourcesid type

            $table->timestamps();

            // Foreign key to sfdcomplaints
            $table->foreign('complaint_id')
                ->references('ComplaintID')
                ->on('sfdcomplaints')
                ->onDelete('cascade');

            // Foreign key to comsources
            $table->foreign('comsource_id')
                ->references('comsourcesid')
                ->on('comsources')
                ->onDelete('cascade');

            // Prevent duplicates
            $table->unique(['complaint_id', 'comsource_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_sources');
    }
};
