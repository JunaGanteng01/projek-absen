<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Membuat seluruh skema inti sistem absensi beserta FK, indeks, dan constraint. */
class CreateAttendanceSchema extends Migration
{
    public function up(): void
    {
        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'name'=>['type'=>'VARCHAR','constraint'=>100],'code'=>['type'=>'VARCHAR','constraint'=>30,'unique'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],'deleted_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->createTable('departments');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'name'=>['type'=>'VARCHAR','constraint'=>100],'latitude'=>['type'=>'DECIMAL','constraint'=>'10,7'],'longitude'=>['type'=>'DECIMAL','constraint'=>'10,7'],'radius_meters'=>['type'=>'INT','unsigned'=>true,'default'=>100],'is_active'=>['type'=>'BOOLEAN','default'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],'deleted_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey('is_active'); $this->forge->createTable('office_locations');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'name'=>['type'=>'VARCHAR','constraint'=>80],'start_time'=>['type'=>'TIME'],'end_time'=>['type'=>'TIME'],'late_tolerance_minutes'=>['type'=>'INT','unsigned'=>true,'default'=>0],'early_leave_tolerance_minutes'=>['type'=>'INT','unsigned'=>true,'default'=>0],'is_active'=>['type'=>'BOOLEAN','default'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],'deleted_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->createTable('shifts');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'department_id'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'shift_id'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'name'=>['type'=>'VARCHAR','constraint'=>120],'email'=>['type'=>'VARCHAR','constraint'=>190,'unique'=>true],'employee_no'=>['type'=>'VARCHAR','constraint'=>40,'unique'=>true],'password_hash'=>['type'=>'VARCHAR','constraint'=>255],'role'=>['type'=>'ENUM','constraint'=>['admin','staff'],'default'=>'staff'],'is_active'=>['type'=>'BOOLEAN','default'=>true],'last_login_at'=>['type'=>'DATETIME','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true],'deleted_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey(['department_id','role']); $this->forge->addForeignKey('department_id','departments','id','SET NULL','RESTRICT'); $this->forge->addForeignKey('shift_id','shifts','id','SET NULL','RESTRICT'); $this->forge->createTable('users');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'BIGINT','unsigned'=>true],'office_location_id'=>['type'=>'BIGINT','unsigned'=>true],'attendance_date'=>['type'=>'DATE'],'check_in'=>['type'=>'DATETIME','null'=>true],'check_out'=>['type'=>'DATETIME','null'=>true],'check_in_latitude'=>['type'=>'DECIMAL','constraint'=>'10,7','null'=>true],'check_in_longitude'=>['type'=>'DECIMAL','constraint'=>'10,7','null'=>true],'check_out_latitude'=>['type'=>'DECIMAL','constraint'=>'10,7','null'=>true],'check_out_longitude'=>['type'=>'DECIMAL','constraint'=>'10,7','null'=>true],'check_in_distance'=>['type'=>'DECIMAL','constraint'=>'8,2','null'=>true],'check_out_distance'=>['type'=>'DECIMAL','constraint'=>'8,2','null'=>true],'check_in_status'=>['type'=>'ENUM','constraint'=>['Tepat Waktu','Terlambat'],'null'=>true],'check_out_status'=>['type'=>'ENUM','constraint'=>['Pulang Cepat','Pulang Normal','Lembur'],'null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey(['user_id','attendance_date']); $this->forge->addKey(['attendance_date','check_in_status']); $this->forge->addForeignKey('user_id','users','id','CASCADE','RESTRICT'); $this->forge->addForeignKey('office_location_id','office_locations','id','RESTRICT','RESTRICT'); $this->forge->createTable('attendance');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'token_hash'=>['type'=>'CHAR','constraint'=>64,'unique'=>true],'expires_at'=>['type'=>'DATETIME'],'used_at'=>['type'=>'DATETIME','null'=>true],'used_by'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey(['expires_at','used_at']); $this->forge->addForeignKey('used_by','users','id','SET NULL','RESTRICT'); $this->forge->createTable('attendance_tokens');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'BIGINT','unsigned'=>true],'fingerprint_hash'=>['type'=>'CHAR','constraint'=>64],'name'=>['type'=>'VARCHAR','constraint'=>150,'null'=>true],'last_ip'=>['type'=>'VARCHAR','constraint'=>45,'null'=>true],'last_seen_at'=>['type'=>'DATETIME','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addUniqueKey(['user_id','fingerprint_hash']); $this->forge->addForeignKey('user_id','users','id','CASCADE','RESTRICT'); $this->forge->createTable('devices');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'BIGINT','unsigned'=>true],'start_date'=>['type'=>'DATE'],'end_date'=>['type'=>'DATE'],'type'=>['type'=>'ENUM','constraint'=>['izin','sakit','cuti']],'reason'=>['type'=>'TEXT'],'status'=>['type'=>'ENUM','constraint'=>['pending','approved','rejected'],'default'=>'pending'],'approved_by'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true],'updated_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey(['status','start_date']); $this->forge->addForeignKey('user_id','users','id','CASCADE','RESTRICT'); $this->forge->addForeignKey('approved_by','users','id','SET NULL','RESTRICT'); $this->forge->createTable('leave_requests');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'user_id'=>['type'=>'BIGINT','unsigned'=>true],'title'=>['type'=>'VARCHAR','constraint'=>150],'message'=>['type'=>'TEXT'],'read_at'=>['type'=>'DATETIME','null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey(['user_id','read_at']); $this->forge->addForeignKey('user_id','users','id','CASCADE','RESTRICT'); $this->forge->createTable('notifications');

        $this->forge->addField(['id'=>['type'=>'BIGINT','unsigned'=>true,'auto_increment'=>true],'actor_id'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'action'=>['type'=>'VARCHAR','constraint'=>100],'entity'=>['type'=>'VARCHAR','constraint'=>100],'entity_id'=>['type'=>'BIGINT','unsigned'=>true,'null'=>true],'old_values'=>['type'=>'JSON','null'=>true],'new_values'=>['type'=>'JSON','null'=>true],'ip_address'=>['type'=>'VARCHAR','constraint'=>45,'null'=>true],'created_at'=>['type'=>'DATETIME','null'=>true]]);
        $this->forge->addKey('id', true); $this->forge->addKey(['entity','entity_id']); $this->forge->addForeignKey('actor_id','users','id','SET NULL','RESTRICT'); $this->forge->createTable('audit_logs');
    }

    public function down(): void
    {
        foreach (['audit_logs','notifications','leave_requests','devices','attendance_tokens','attendance','users','shifts','office_locations','departments'] as $table) $this->forge->dropTable($table, true);
    }
}
