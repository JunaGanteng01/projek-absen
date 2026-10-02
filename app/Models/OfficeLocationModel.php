<?php
namespace App\Models;
/** CRUD titik/radius kantor; hasilnya dipakai LocationService. */
class OfficeLocationModel extends BaseAppModel { protected $table='office_locations'; protected $allowedFields=['name','latitude','longitude','radius_meters','is_active']; protected $useSoftDeletes=true; }
