<?php

namespace App\Services;

use App\Models\OfficeLocationModel;
use RuntimeException;

/**
 * LocationService
 *
 * Mencari kantor aktif terdekat menggunakan Haversine. Input koordinat browser;
 * output kantor dan jarak meter, atau exception bila di luar semua radius.
 */
class LocationService
{
    public function validate(float $latitude, float $longitude): array
    {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) throw new RuntimeException('Koordinat GPS tidak valid.');
        $locations=(new OfficeLocationModel())->where('is_active',1)->findAll(); $nearest=null;
        foreach ($locations as $location) {
            $distance=$this->haversine($latitude,$longitude,(float)$location['latitude'],(float)$location['longitude']);
            if ($nearest===null || $distance<$nearest['distance']) $nearest=['location'=>$location,'distance'=>$distance];
        }
        if (! $nearest) throw new RuntimeException('Belum ada lokasi kantor aktif.');
        if ($nearest['distance']>(float)$nearest['location']['radius_meters']) throw new RuntimeException('Anda berada di luar radius kantor ('.round($nearest['distance']).' meter).');
        return $nearest;
    }

    /** Rumus Haversine dengan radius bumi 6.371.000 meter. */
    public function haversine(float $lat1,float $lon1,float $lat2,float $lon2): float
    {
        $dLat=deg2rad($lat2-$lat1); $dLon=deg2rad($lon2-$lon1);
        $a=sin($dLat/2)**2+cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)**2;
        return 6371000*2*atan2(sqrt($a),sqrt(1-$a));
    }
}
