<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;


class TypeController extends Controller
{
    /**
     * TypeController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:type.manage');
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
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //TODO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //TODO
    }
}
