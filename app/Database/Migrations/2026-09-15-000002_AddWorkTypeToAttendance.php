<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Menambah batas mulai Part Time dan klasifikasi kerja pada absensi. */
class AddWorkTypeToAttendance extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('shifts', [
            'part_time_start' => ['type'=>'TIME','default'=>'13:00:00','after'=>'start_time'],
        ]);
        $this->forge->modifyColumn('attendance', [
            'check_in_status' => ['name'=>'check_in_status','type'=>'ENUM','constraint'=>['Tepat Waktu','Hadir','Terlambat'],'null'=>true],
        ]);
        $this->forge->addColumn('attendance', [
            'work_type' => ['type'=>'ENUM','constraint'=>['Full Time','Part Time'],'null'=>true,'after'=>'check_in_status'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('attendance','work_type');
        $this->forge->modifyColumn('attendance', [
            'check_in_status' => ['name'=>'check_in_status','type'=>'ENUM','constraint'=>['Tepat Waktu','Terlambat'],'null'=>true],
        ]);
        $this->forge->dropColumn('shifts','part_time_start');
    }
}
