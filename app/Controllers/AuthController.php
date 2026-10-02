<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * AuthController
 *
 * Menampilkan login, memverifikasi password hash, meregenerasi session, dan
 * logout. Input POST email/password; output redirect dashboard sesuai role.
 */
class AuthController extends BaseController
{
    public function login()
    {
        if (session('user_id')) return redirect()->to('/'.session('role').'/dashboard');
        if ($this->request->getMethod()!=='POST') return view('auth/login');
        $data=$this->request->getPost(['email','password']);
        if (! $this->validateData($data,['email'=>'required|valid_email|max_length[190]','password'=>'required|max_length[200]'])) return view('auth/login',['validation'=>$this->validator]);
        $model=new UserModel(); $user=$model->where('email',strtolower(trim($data['email'])))->where('is_active',1)->first();
        if (! $user || ! password_verify($data['password'],$user['password_hash'])) return redirect()->back()->withInput()->with('error','Email atau password salah.');
        session()->regenerate(true); session()->set(['user_id'=>(int)$user['id'],'name'=>$user['name'],'role'=>$user['role'],'department_id'=>$user['department_id']]);
        $model->update($user['id'],['last_login_at'=>date('Y-m-d H:i:s')]);
        return redirect()->to('/'.$user['role'].'/dashboard');
    }
    public function logout() { session()->destroy(); return redirect()->to('/login')->with('success','Anda telah logout.'); }
}
