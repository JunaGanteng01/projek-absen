<?php

namespace App\Controllers;

use App\Models\AttendanceModel;

/** Menyajikan dashboard dan riwayat milik staff yang sedang login. */
class StaffController extends BaseController
{
    public function dashboard()
    {
        $model=new AttendanceModel(); $today=$model->where('user_id',session('user_id'))->where('attendance_date',date('Y-m-d'))->first();
        $history=$model->select('attendance.*, office_locations.name AS location_name')->join('office_locations','office_locations.id=attendance.office_location_id')->where('user_id',session('user_id'))->orderBy('attendance_date','DESC')->findAll(10);
        return view('staff/dashboard',['title'=>'Dashboard Staff','today'=>$today,'history'=>$history]);
    }
    public function history() { $model=new AttendanceModel(); $rows=$model->where('user_id',session('user_id'))->orderBy('attendance_date','DESC')->paginate(25); return view('staff/history',['title'=>'Riwayat Absensi','rows'=>$rows,'pager'=>$model->pager]); }
}
