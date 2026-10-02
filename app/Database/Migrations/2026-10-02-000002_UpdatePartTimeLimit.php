<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePartTimeLimit extends Migration
{
    public function up(): void
    {
        $this->db->table('shifts')->where('name', 'Reguler')->where('part_time_start', '13:00:00')
            ->update(['part_time_start' => '14:00:00']);
    }

    public function down(): void
    {
        $this->db->table('shifts')->where('name', 'Reguler')->where('part_time_start', '14:00:00')
            ->update(['part_time_start' => '13:00:00']);
    }
}
