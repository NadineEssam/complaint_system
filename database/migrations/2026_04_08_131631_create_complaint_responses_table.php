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
         Schema::dropIfExists('complaint_responses');

        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id(); // primary key for this table

            // Must match ComplaintID type in sfdcomplaints (int)
            $table->integer('complaint_id');

            // Columns from your model
            $table->integer('ComplaintStatus')->nullable();
            $table->text('ComplaintText')->nullable();
            $table->integer('ComplaintService')->nullable();
            $table->integer('fk_close_reason_id')->nullable();
            $table->integer('fk_close_reason_classify_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            // No timestamps as per your model
            // $table->timestamps(); // not needed

            // Foreign key to sfdcomplaints
            $table->foreign('complaint_id')
                  ->references('ComplaintID')
                  ->on('sfdcomplaints')
                  ->onDelete('cascade');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_responses');
    }
};