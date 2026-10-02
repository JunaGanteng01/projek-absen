<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/** Membuat laporan terfilter dan ekspor XLSX/PDF dari query yang sama. */
class ReportController extends BaseController
{
    public function index() { return view('reports/index',['title'=>'Laporan Absensi','rows'=>$this->rows()]); }
    public function excel()
    {
        $rows=$this->rows(); $from=$this->request->getGet('from')?:date('Y-m-01'); $to=$this->request->getGet('to')?:date('Y-m-d');
        $spreadsheet=new Spreadsheet(); $sheet=$spreadsheet->getActiveSheet(); $sheet->setTitle('Absensi Gabungan');
        $sheet->mergeCells('A1:I1')->setCellValue('A1','LAPORAN ABSENSI FULL TIME & PART TIME');
        $sheet->mergeCells('A2:I2')->setCellValue('A2','Periode: '.$from.' s.d. '.$to);
        $sheet->fromArray(['Tanggal','NIP','Nama','Divisi','Tipe Kerja','Jam Masuk','Status Hadir','Jam Pulang','Status Pulang'],null,'A4'); $row=5;
        foreach($rows as $r)$sheet->fromArray([$r['attendance_date'],$r['employee_no'],$r['name'],$r['department_name'],$r['work_type']?:'-',$r['check_in'],$r['check_in_status'],$r['check_out'],$r['check_out_status']],null,'A'.$row++);
        $last=max(4,$row-1); $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(16); $sheet->getStyle('A1:I2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:I4')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF'); $sheet->getStyle('A4:I4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2563EB');
        $sheet->getStyle('A4:I'.$last)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFB8C2CC'); $sheet->getStyle('A4:I'.$last)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        foreach(['A'=>13,'B'=>13,'C'=>24,'D'=>20,'E'=>14,'F'=>20,'G'=>16,'H'=>20,'I'=>18] as $col=>$width)$sheet->getColumnDimension($col)->setWidth($width);
        $sheet->freezePane('A5'); $sheet->setAutoFilter('A4:I'.$last); $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)->setPaperSize(PageSetup::PAPERSIZE_A4)->setFitToWidth(1)->setFitToHeight(0)->setPrintArea('A1:I'.$last); $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4,4); $sheet->getPageMargins()->setTop(.4)->setRight(.3)->setBottom(.4)->setLeft(.3); $sheet->getHeaderFooter()->setOddFooter('&LDicetak: &D &T&CAbsensiQR&RHalaman &P / &N');
        $tmp=tempnam(WRITEPATH,'xlsx'); (new Xlsx($spreadsheet))->save($tmp); return $this->response->download($tmp,null)->setFileName('laporan-absensi-gabungan-'.$from.'-'.$to.'.xlsx');
    }
    public function pdf()
    {
        $dompdf=new Dompdf(); $dompdf->loadHtml(view('reports/pdf',['rows'=>$this->rows()])); $dompdf->setPaper('A4','landscape'); $dompdf->render(); return $this->response->setHeader('Content-Type','application/pdf')->setHeader('Content-Disposition','attachment; filename="laporan-absensi.pdf"')->setBody($dompdf->output());
    }
    private function rows(): array
    {
        $from=$this->request->getGet('from')?:date('Y-m-01'); $to=$this->request->getGet('to')?:date('Y-m-d');
        $builder=db_connect()->table('attendance a')->select('a.*,u.name,u.employee_no,d.name department_name')->join('users u','u.id=a.user_id')->join('departments d','d.id=u.department_id','left')->where('a.attendance_date >=',$from)->where('a.attendance_date <=',$to);
        if($dep=(int)$this->request->getGet('department_id'))$builder->where('u.department_id',$dep); return $builder->orderBy('a.attendance_date','DESC')->get()->getResultArray();
    }
}
