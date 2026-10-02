<?php
namespace App\Models;
/** Akses data akun admin/staff. Input berupa array field yang diizinkan; output array pengguna. */
class UserModel extends BaseAppModel { protected $table='users'; protected $allowedFields=['department_id','shift_id','name','email','employee_no','password_hash','role','is_active','last_login_at']; protected $useSoftDeletes=true; }
