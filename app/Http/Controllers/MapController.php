<?php

namespace App\Http\Controllers;

class MapController extends Controller
{
    protected static $validZone = ['A', 'B', 'C'];

    public function index($zone = null)
    {
        if (empty($zone)) {
            $imgUrl = asset('img/map/map.jpg');
            $thumbnailUrl = asset('img/map/thumbnail_map.jpg');
        } else {
            $zone = strtoupper($zone);
            if (!in_array($zone, static::$validZone)) {
                return redirect()->route('map.index');
            }
            $imgUrl = asset('img/map/zone_' . strtolower($zone) . '.jpg');
            $thumbnailUrl = asset('img/map/thumbnail_zone_' . strtolower($zone) . '.jpg');
        }

        return view('map.index', compact(['zone', 'imgUrl', 'thumbnailUrl']));
    }
}
