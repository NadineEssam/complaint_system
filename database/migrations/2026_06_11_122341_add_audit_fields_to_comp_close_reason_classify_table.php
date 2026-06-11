<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comp_close_reason_classify', function (Blueprint $table) {
            if (!Schema::hasColumn('comp_close_reason_classify', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('fk_close_reason_id');
            }
            if (!Schema::hasColumn('comp_close_reason_classify', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('comp_close_reason_classify', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('updated_by');
            }
            if (!Schema::hasColumn('comp_close_reason_classify', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            // Foreign keys
            if (Schema::hasColumn('comp_close_reason_classify', 'created_by')) {
                $table->foreign('created_by')->references('id')->on('users_groups')->onDelete('set null');
            }
            if (Schema::hasColumn('comp_close_reason_classify', 'updated_by')) {
                $table->foreign('updated_by')->references('id')->on('users_groups')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comp_close_reason_classify', function (Blueprint $table) {
            if (Schema::hasColumn('comp_close_reason_classify', 'updated_by')) {
                $table->dropForeign(['updated_by']);
            }
            if (Schema::hasColumn('comp_close_reason_classify', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
            $table->dropColumnIfExists(['created_by', 'updated_by', 'created_at', 'updated_at']);
        });
    }
};