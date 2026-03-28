<?php

namespace App\Services;

use App\Models\Region;

class RegionService
{
    public static function detect(float $latitude, float $longitude): bool
    {
        $regions = Region::where('active', 1)->get();

        foreach ($regions as $region) {
            if (GeoService::pointInPolygon($latitude, $longitude, $region->coordinates)) {
                return true;
            }
        }

        return false;
    }
}
