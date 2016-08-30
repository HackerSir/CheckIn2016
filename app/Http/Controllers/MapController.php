<?php

namespace App\Http\Controllers;

class MapController extends Controller
{
    protected static $validZone = ['A', 'B', 'C'];

    public function index()
    {
        return view('map.index');
    }

    public function zone($zone)
    {
        $zone = strtoupper($zone);
        if (!in_array($zone, static::$validZone)) {
            return redirect()->route('map.index');
        }
        $imgUrl = asset('img/map/zone_' . strtolower($zone) . '.jpg');

        return view('map.zone', compact(['zone', 'imgUrl']));
    }
}
