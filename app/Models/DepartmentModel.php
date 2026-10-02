<?php
namespace App\Models;
/** CRUD divisi; dipanggil CrudController dan filter dashboard. */
class DepartmentModel extends BaseAppModel { protected $table='departments'; protected $allowedFields=['name','code']; protected $useSoftDeletes=true; }
