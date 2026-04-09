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
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id(); // primary key

            // Match type and signed/unsigned of ComplaintID
            $table->integer('complaint_id');

            $table->integer('ComplaintStatus')->nullable();
            $table->text('ComplaintText')->nullable();
            $table->integer('ComplaintService')->nullable();
            $table->integer('fk_close_reason_id')->nullable();
            $table->integer('fk_close_reason_classify_id')->nullable();
            $table->date('entryDate')->nullable();
            $table->text('UpdateUser')->nullable();
            $table->text('ComplainterName2')->nullable();
            $table->text('StatusDetails')->nullable();
            $table->integer('department')->nullable();
            $table->text('ComplainerGender')->nullable();

            $table->timestamps();

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
