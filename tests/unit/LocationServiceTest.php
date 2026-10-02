<?php

use App\Services\LocationService;
use CodeIgniter\Test\CIUnitTestCase;

final class LocationServiceTest extends CIUnitTestCase
{
    public function testSameCoordinateHasZeroDistance(): void
    {
        $this->assertEqualsWithDelta(0.0, (new LocationService())->haversine(-6.2, 106.8, -6.2, 106.8), 0.001);
    }

    public function testOneDegreeLatitudeIsAbout111Kilometers(): void
    {
        $this->assertEqualsWithDelta(111194.9, (new LocationService())->haversine(0, 0, 1, 0), 5.0);
    }
}
