<?php

namespace App\Services;

use App\Models\AttendanceTokenModel;
use CodeIgniter\I18n\Time;
use RuntimeException;

/**
 * QrTokenService
 *
 * Membuat payload QR terenkripsi, menyimpan hanya SHA-256-nya, memvalidasi
 * kedaluwarsa, dan mengunci token sekali pakai. Dipanggil AttendanceController.
 */
class QrTokenService
{
    private AttendanceTokenModel $tokens;
    public function __construct() { $this->tokens=new AttendanceTokenModel(); }

    /** Menghasilkan token baru dengan TTL 30 detik menurut waktu server WIB. */
    public function issue(): array
    {
        $now=Time::now('Asia/Jakarta'); $expires=$now->addSeconds(30);
        // Payload biner 12 byte menjaga QR tetap renggang dan mudah dipindai:
        // 4 byte expiry Unix + 8 byte nonce kriptografis.
        $payload=pack('N', $expires->getTimestamp()).random_bytes(8);
        $encrypted=service('encrypter')->encrypt($payload);
        $token=rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
        $this->tokens->insert(['token_hash'=>hash('sha256',$token),'expires_at'=>$expires->toDateTimeString()]);
        return ['token'=>$token,'expires_at'=>$expires->toDateTimeString(),'ttl'=>30];
    }

    /** Memvalidasi payload + database lalu atomik menandainya terpakai. */
    public function consume(string $token, int $userId): void
    {
        try {
            $raw=base64_decode(strtr($token, '-_', '+/'), true);
            $payload=service('encrypter')->decrypt($raw ?: '');
            if (strlen($payload)!==12) throw new RuntimeException('Panjang payload tidak valid.');
            $decoded=unpack('Nexp', substr($payload,0,4));
        } catch (\Throwable) { throw new RuntimeException('Token QR tidak valid.'); }
        if (($decoded['exp']??0) < Time::now('Asia/Jakarta')->getTimestamp()) throw new RuntimeException('Token QR sudah kedaluwarsa.');
        $now=Time::now('Asia/Jakarta')->toDateTimeString();
        $row=$this->tokens->where('token_hash',hash('sha256',$token))->where('used_at',null)->where('expires_at >=',$now)->first();
        if (! $row) throw new RuntimeException('Token QR sudah digunakan atau kedaluwarsa.');
        $db=db_connect();
        $updated=$this->tokens->builder()->where('id',$row['id'])->where('used_at',null)->update(['used_at'=>$now,'used_by'=>$userId]);
        if (! $updated || $db->affectedRows()!==1) throw new RuntimeException('Token QR sudah digunakan.');
    }
}
