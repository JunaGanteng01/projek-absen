<?php
namespace App\Models;
/** Menyimpan satu baris absensi per pengguna per tanggal server. */
class AttendanceModel extends BaseAppModel { protected $table='attendance'; protected $allowedFields=['user_id','office_location_id','attendance_date','check_in','check_out','check_in_latitude','check_in_longitude','check_out_latitude','check_out_longitude','check_in_distance','check_out_distance','check_in_status','work_type','check_out_status']; }
