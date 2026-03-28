<?php

namespace App\Services;

class GeoService
{
    public static function pointInPolygon(float $latitude, float $longitude, array $polygon): bool
    {
        $inside = false;

        for ($i = 0, $j = count($polygon) - 1; $i < count($polygon); $j = $i++) {
            [$xi, $yi] = $polygon[$i];
            [$xj, $yj] = $polygon[$j];

            $intersect =
                (($yi > $longitude) !== ($yj > $longitude)) &&
                ($latitude < ($xj - $xi) * ($longitude - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }
}
