<?php
namespace App\Models;
/** Menyimpan hash token QR sekali pakai dan masa berlakunya. */
class AttendanceTokenModel extends BaseAppModel { protected $table='attendance_tokens'; protected $allowedFields=['token_hash','expires_at','used_at','used_by']; protected $updatedField=''; }
