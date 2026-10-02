<?php
namespace App\Models;
/** Append-only audit log perubahan penting oleh admin dan aktivitas absensi. */
class AuditLogModel extends BaseAppModel { protected $table='audit_logs'; protected $allowedFields=['actor_id','action','entity','entity_id','old_values','new_values','ip_address']; protected $updatedField=''; }
