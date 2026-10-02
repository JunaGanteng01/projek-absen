<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFullTimeLimit extends Migration
{
    public function up(): void
    {
        $this->db->table('shifts')->where('name', 'Reguler')->where('start_time', '09:00:00')
            ->where('late_tolerance_minutes', 0)->update(['start_time' => '10:00:00']);
    }

    public function down(): void
    {
        $this->db->table('shifts')->where('name', 'Reguler')->where('start_time', '10:00:00')
            ->where('late_tolerance_minutes', 0)->update(['start_time' => '09:00:00']);
    }
}
