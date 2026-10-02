<?php

namespace App\Controllers;

/** Dashboard admin: statistik, grafik 7 hari, serta koordinat absensi terbaru. */
class AdminController extends BaseController
{
    public function dashboard() { return view('admin/dashboard',['title'=>'Dashboard Admin']+$this->dashboardData()); }
    public function qr() { return view('admin/qr',['title'=>'QR Absensi']); }
    public function stats() { return $this->response->setJSON(['success'=>true,'data'=>$this->dashboardData()]); }
    private function dashboardData(): array
    {
        $db=db_connect(); $date=$this->request->getGet('date')?:date('Y-m-d'); $department=(int)($this->request->getGet('department_id')?:0);
        $staff=$db->table('users')->where('role','staff')->where('deleted_at',null)->where('is_active',1); if($department)$staff->where('department_id',$department); $staffCount=$staff->countAllResults();
        $attendance=$db->table('attendance a')->join('users u','u.id=a.user_id')->where('a.attendance_date',$date); if($department)$attendance->where('u.department_id',$department); $rows=$attendance->get()->getResultArray();
        $stats=['present'=>count($rows),'late'=>count(array_filter($rows,fn($r)=>$r['check_in_status']==='Terlambat')),'absent'=>max(0,$staffCount-count($rows)),'working'=>count(array_filter($rows,fn($r)=>$r['check_in']&&!$r['check_out'])),'finished'=>count(array_filter($rows,fn($r)=>$r['check_out'])),'overtime'=>count(array_filter($rows,fn($r)=>$r['check_out_status']==='Lembur'))];
        $chart=[]; for($i=6;$i>=0;$i--){$d=date('Y-m-d',strtotime("-$i days"));$chart[]=['date'=>$d,'total'=>$db->table('attendance')->where('attendance_date',$d)->countAllResults()];}
        $locations=$db->table('attendance a')->select('u.name,a.check_in_latitude latitude,a.check_in_longitude longitude,a.check_in,a.check_in_status')->join('users u','u.id=a.user_id')->where('a.attendance_date',$date)->where('a.check_in_latitude IS NOT NULL',null,false)->get()->getResultArray();
        return compact('stats','chart','locations','date')+['departments'=>$db->table('departments')->where('deleted_at',null)->get()->getResultArray()];
    }
}
