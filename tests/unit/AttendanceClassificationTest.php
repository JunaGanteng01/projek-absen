<?php

use App\Services\AttendanceService;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;

final class AttendanceClassificationTest extends CIUnitTestCase
{
    private array $shift=['start_time'=>'10:00:00','part_time_start'=>'14:00:00','late_tolerance_minutes'=>0];

    public function testTenOclockIsFullTime(): void
    {
        $this->assertSame(['status'=>'Tepat Waktu','work_type'=>'Full Time'],(new AttendanceService())->classifyCheckIn(Time::parse('2026-09-15 10:00:00','Asia/Jakarta'),$this->shift));
    }

    public function testImmediatelyAfterTenIsPartTime(): void
    {
        $this->assertSame(['status'=>'Hadir','work_type'=>'Part Time'],(new AttendanceService())->classifyCheckIn(Time::parse('2026-09-15 10:00:01','Asia/Jakarta'),$this->shift));
    }

    public function testBeforeTwoPmIsPresentPartTime(): void
    {
        $this->assertSame(['status'=>'Hadir','work_type'=>'Part Time'],(new AttendanceService())->classifyCheckIn(Time::parse('2026-09-15 14:00:00','Asia/Jakarta'),$this->shift));
    }

    public function testAfterTwoPmIsLatePartTime(): void
    {
        $this->assertSame(['status'=>'Terlambat','work_type'=>'Part Time'],(new AttendanceService())->classifyCheckIn(Time::parse('2026-09-15 14:00:01','Asia/Jakarta'),$this->shift));
    }
}
