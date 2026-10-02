<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Mengisi data awal aman untuk demo; ganti password setelah login pertama. */
class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $this->db->table('departments')->insertBatch([
            ['name'=>'Teknologi','code'=>'IT','created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Operasional','code'=>'OPS','created_at'=>$now,'updated_at'=>$now],
        ]);
        $this->db->table('office_locations')->insert(['name'=>'PT. Alma Indonesia Raya | Almai','latitude'=>-8.6636260,'longitude'=>115.2303082,'radius_meters'=>150,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now]);
        $this->db->table('shifts')->insert(['name'=>'Reguler','start_time'=>'10:00:00','part_time_start'=>'14:00:00','end_time'=>'18:00:00','late_tolerance_minutes'=>0,'early_leave_tolerance_minutes'=>0,'is_active'=>1,'created_at'=>$now,'updated_at'=>$now]);
        $departmentId = $this->db->table('departments')->where('code','IT')->get()->getRow()->id;
        $shiftId = $this->db->table('shifts')->get()->getRow()->id;
        $this->db->table('users')->insertBatch([
            ['department_id'=>$departmentId,'shift_id'=>$shiftId,'name'=>'Administrator','email'=>'admin@example.com','employee_no'=>'ADM001','password_hash'=>password_hash('Admin123!', PASSWORD_DEFAULT),'role'=>'admin','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['department_id'=>$departmentId,'shift_id'=>$shiftId,'name'=>'Budi Staff','email'=>'staff@example.com','employee_no'=>'STF001','password_hash'=>password_hash('Staff123!', PASSWORD_DEFAULT),'role'=>'staff','is_active'=>1,'created_at'=>$now,'updated_at'=>$now],
        ]);
    }
}
