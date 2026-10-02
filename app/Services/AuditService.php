<?php
namespace App\Services;
use App\Models\AuditLogModel;
/** Menulis audit trail perubahan. Input action/entity/nilai; output id log. */
class AuditService { public function record(string $action,string $entity,?int $entityId=null,?array $old=null,?array $new=null): int { return (int)(new AuditLogModel())->insert(['actor_id'=>session('user_id'),'action'=>$action,'entity'=>$entity,'entity_id'=>$entityId,'old_values'=>$old?json_encode($old):null,'new_values'=>$new?json_encode($new):null,'ip_address'=>service('request')->getIPAddress()],true); } }
