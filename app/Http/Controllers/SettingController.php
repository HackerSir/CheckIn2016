<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();
        return view('setting.index', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'data' => 'max:65536'
        ]);

        $setting->update([
            'data' => $request->get('value')
        ]);

        $json = [
            'success' => true
        ];

        return response()->json($json);
    }
}
