<?php

namespace App\Http\Controllers;

use App\Booth;
use Illuminate\Http\Request;


class BoothController extends Controller
{
    /**
     * TypeController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:type.manage', [
            'except' => [
                'index',
                'show',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO
    }

    /**
     * Display the specified resource.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function show(Booth $booth)
    {
        //TODO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function edit(Booth $booth)
    {
        //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booth $booth)
    {
        //TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booth $booth)
    {
        //TODO
    }
}
