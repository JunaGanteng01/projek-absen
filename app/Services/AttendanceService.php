<?php

namespace App\Services;

use App\Models\AttendanceModel;
use App\Models\ShiftModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use RuntimeException;

/**
 * AttendanceService
 *
 * Mengorkestrasi validasi QR, GPS, jam server, check-in/check-out dan audit.
 * Input: user, token, koordinat. Output: tipe aksi, status, dan waktu WIB.
 */
class AttendanceService
{
    public function scan(int $userId,string $token,float $lat,float $lon): array
    {
        $user=(new UserModel())->find($userId); if (! $user || ! $user['is_active']) throw new RuntimeException('Akun tidak aktif.');
        $shift=(new ShiftModel())->find($user['shift_id']); if (! $shift) throw new RuntimeException('Shift belum ditentukan.');
        $location=(new LocationService())->validate($lat,$lon); $now=Time::now('Asia/Jakarta'); $date=$now->toDateString();
        $model=new AttendanceModel(); $db=db_connect(); $db->transBegin();
        try {
            (new QrTokenService())->consume($token,$userId);
            $attendance=$model->where('user_id',$userId)->where('attendance_date',$date)->first();
            if (! $attendance) {
                ['status'=>$status,'work_type'=>$workType]=$this->classifyCheckIn($now,$shift);
                $id=$model->insert(['user_id'=>$userId,'office_location_id'=>$location['location']['id'],'attendance_date'=>$date,'check_in'=>$now->toDateTimeString(),'check_in_latitude'=>$lat,'check_in_longitude'=>$lon,'check_in_distance'=>$location['distance'],'check_in_status'=>$status,'work_type'=>$workType],true);
                $result=['action'=>'check_in','status'=>$status,'work_type'=>$workType,'time'=>$now->toDateTimeString(),'attendance_id'=>(int)$id];
            } else {
                if ($attendance['check_out']) throw new RuntimeException('Absensi hari ini sudah lengkap.');
                $end=Time::parse($date.' '.$shift['end_time'],'Asia/Jakarta');
                $earliestNormal=$end->subMinutes((int)$shift['early_leave_tolerance_minutes']);
                $status=$now->isBefore($earliestNormal)?'Pulang Cepat':($now->isAfter($end)?'Lembur':'Pulang Normal');
                $model->update($attendance['id'],['check_out'=>$now->toDateTimeString(),'check_out_latitude'=>$lat,'check_out_longitude'=>$lon,'check_out_distance'=>$location['distance'],'check_out_status'=>$status]);
                $result=['action'=>'check_out','status'=>$status,'time'=>$now->toDateTimeString(),'attendance_id'=>(int)$attendance['id']];
            }
            (new AuditService())->record($result['action'],'attendance',$result['attendance_id'],null,$result);
            $db->transCommit(); return $result;
        } catch (\Throwable $e) { $db->transRollback(); throw $e; }
    }

    /** Mengklasifikasikan check-in tanpa akses database agar aturan mudah diuji. */
    public function classifyCheckIn(Time $now,array $shift): array
    {
        $date=$now->toDateString();
        $fullTimeLimit=Time::parse($date.' '.$shift['start_time'],'Asia/Jakarta')->addMinutes((int)($shift['late_tolerance_minutes']??0));
        $partTimeLimit=Time::parse($date.' '.($shift['part_time_start']??'14:00:00'),'Asia/Jakarta');
        if (! $now->isAfter($fullTimeLimit)) return ['status'=>'Tepat Waktu','work_type'=>'Full Time'];
        if (! $now->isAfter($partTimeLimit)) return ['status'=>'Hadir','work_type'=>'Part Time'];
        return ['status'=>'Terlambat','work_type'=>'Part Time'];
    }
}
