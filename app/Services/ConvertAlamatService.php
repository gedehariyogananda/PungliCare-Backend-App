<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;

class ConvertAlamatService
{
    public function getAddressFromCoordinates($latitude, $longitude)
    {
        $cacheKey = "address_{$latitude}_{$longitude}";
        $cachedAddress = Cache::get($cacheKey);

        if ($cachedAddress) {
            return $cachedAddress;
        }

        $apiUrl = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$latitude}&lon={$longitude}";

        // Membuat koneksi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $alamat = $data['display_name'] ?? '';

        // Cache the address for 1 day
        Cache::put($cacheKey, $alamat, now()->addDay());

        return $alamat;
    }
}
