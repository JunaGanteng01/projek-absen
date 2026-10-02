<?php
namespace App\Models;
/** CRUD aturan jam dan toleransi shift; dipakai AttendanceService. */
class ShiftModel extends BaseAppModel { protected $table='shifts'; protected $allowedFields=['name','start_time','part_time_start','end_time','late_tolerance_minutes','early_leave_tolerance_minutes','is_active']; protected $useSoftDeletes=true; }
