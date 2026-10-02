<?php

namespace App\Controllers;

use App\Models\{AuditLogModel,DepartmentModel,OfficeLocationModel,ShiftModel,UserModel};
use App\Services\AuditService;

/**
 * CrudController
 *
 * CRUD generik yang dibatasi whitelist untuk staff/divisi/lokasi/shift. Input
 * form tervalidasi; output redirect dan audit log. Tidak menerima nama tabel bebas.
 */
class CrudController extends BaseController
{
    private function config(string $type): array
    {
        $configs=[
            'staff'=>['model'=>UserModel::class,'title'=>'Staff','fields'=>['name'=>'Nama','email'=>'Email','employee_no'=>'NIP','department_id'=>'ID Divisi','shift_id'=>'ID Shift','role'=>'Role','is_active'=>'Aktif'],'rules'=>['name'=>'required|max_length[120]','email'=>'required|valid_email|max_length[190]','employee_no'=>'required|max_length[40]','department_id'=>'permit_empty|integer','shift_id'=>'permit_empty|integer','role'=>'required|in_list[admin,staff]','is_active'=>'required|in_list[0,1]']],
            'departments'=>['model'=>DepartmentModel::class,'title'=>'Divisi','fields'=>['name'=>'Nama','code'=>'Kode'],'rules'=>['name'=>'required|max_length[100]','code'=>'required|alpha_numeric|max_length[30]']],
            'locations'=>['model'=>OfficeLocationModel::class,'title'=>'Lokasi Kantor','fields'=>['name'=>'Nama','latitude'=>'Latitude','longitude'=>'Longitude','radius_meters'=>'Radius (m)','is_active'=>'Aktif'],'rules'=>['name'=>'required|max_length[100]','latitude'=>'required|decimal','longitude'=>'required|decimal','radius_meters'=>'required|integer|greater_than[0]','is_active'=>'required|in_list[0,1]']],
            'shifts'=>['model'=>ShiftModel::class,'title'=>'Shift','fields'=>['name'=>'Nama','start_time'=>'Mulai Full Time','part_time_start'=>'Mulai Part Time','end_time'=>'Jam Pulang','late_tolerance_minutes'=>'Toleransi Full Time','early_leave_tolerance_minutes'=>'Toleransi Pulang','is_active'=>'Aktif'],'rules'=>['name'=>'required|max_length[80]','start_time'=>'required','part_time_start'=>'required','end_time'=>'required','late_tolerance_minutes'=>'required|integer','early_leave_tolerance_minutes'=>'required|integer','is_active'=>'required|in_list[0,1]']],
        ];
        if (! isset($configs[$type])) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); return $configs[$type];
    }
    public function index(string $type) { $c=$this->config($type); $model=new $c['model'](); return view('crud/index',['title'=>'Manajemen '.$c['title'],'type'=>$type,'config'=>$c,'rows'=>$model->orderBy('id','DESC')->findAll(200)]); }
    public function save(string $type)
    {
        $c=$this->config($type); $data=$this->request->getPost(array_keys($c['fields'])); $id=(int)$this->request->getPost('id');
        if (! $this->validateData($data,$c['rules'])) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $model=new $c['model'](); $old=$id?$model->find($id):null;
        if($type==='staff' && ! $id){$data['password_hash']=password_hash((string)($this->request->getPost('password')?:'ChangeMe123!'),PASSWORD_DEFAULT);}
        $id=$id?($model->update($id,$data)?$id:0):(int)$model->insert($data,true); if(!$id)return redirect()->back()->withInput()->with('error','Data gagal disimpan: '.implode(', ',$model->errors()));
        (new AuditService())->record($old?'update':'create',$type,$id,$old,$data); return redirect()->to('/admin/'.$type)->with('success','Data berhasil disimpan.');
    }
    public function delete(string $type,int $id) { $c=$this->config($type); $model=new $c['model'](); $old=$model->find($id); if($old){$model->delete($id);(new AuditService())->record('delete',$type,$id,$old,null);} return redirect()->to('/admin/'.$type)->with('success','Data dihapus.'); }
}
