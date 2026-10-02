<?php

namespace App\Controllers;

use App\Services\AttendanceService;
use App\Services\QrTokenService;

/** Endpoint JSON untuk menerbitkan QR admin dan memproses scan staff. */
class AttendanceController extends BaseController
{
    public function currentToken() { return $this->response->setJSON(['success'=>true,'data'=>(new QrTokenService())->issue()]); }
    public function scan()
    {
        $input=$this->request->getJSON(true) ?: $this->request->getPost();
        if (! $this->validateData($input,['token'=>'required|max_length[2048]','latitude'=>'required|decimal','longitude'=>'required|decimal'])) return $this->response->setStatusCode(422)->setJSON(['success'=>false,'message'=>'Data scan tidak valid.','errors'=>$this->validator->getErrors()]);
        try { $result=(new AttendanceService())->scan((int)session('user_id'),(string)$input['token'],(float)$input['latitude'],(float)$input['longitude']); return $this->response->setJSON(['success'=>true,'message'=>'Absensi berhasil.','data'=>$result,'csrfHash'=>csrf_hash()]); }
        catch (\Throwable $e) { log_message('warning','Scan gagal user {id}: {message}',['id'=>session('user_id'),'message'=>$e->getMessage()]); return $this->response->setStatusCode(422)->setJSON(['success'=>false,'message'=>$e->getMessage(),'csrfHash'=>csrf_hash()]); }
    }
}
