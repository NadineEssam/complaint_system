<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // نقل البيانات القديمة إلى pivot table
        $complaints = DB::table('sfdcomplaints')
            ->whereNotNull('ComplaintSources')
            ->get();

        foreach ($complaints as $complaint) {

            // بعض الأنظمة القديمة ممكن يكون فيها value فاضية أو 0
            if (!$complaint->ComplaintSources) {
                continue;
            }

            // منع التكرار
            $exists = DB::table('complaint_sources')
                ->where('complaint_id', $complaint->ComplaintID)
                ->where('comsource_id', $complaint->ComplaintSources)
                ->exists();

            if (!$exists) {
                DB::table('complaint_sources')->insert([
                    'complaint_id' => $complaint->ComplaintID,
                    'comsource_id' => $complaint->ComplaintSources,
                ]);
            }
        }
    }

    public function down(): void
    {
        // rollback: حذف البيانات المنقولة
        DB::table('complaint_sources')->truncate();
    }
};