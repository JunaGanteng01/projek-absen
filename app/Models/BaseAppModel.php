<?php

namespace App\Models;

use CodeIgniter\Model;

/** Basis model aplikasi: timestamp otomatis, array return, dan proteksi field. */
abstract class BaseAppModel extends Model
{
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $protectFields = true;
}
