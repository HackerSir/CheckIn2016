<?php

namespace App\Http\Controllers;

use App\Traits\ColorTagTrait;
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
        $types = Type::all();

        return view('type.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('type.create-or-edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'target' => 'integer|min:0',
            'color'  => 'required|in:' . implode(',', ColorTagTrait::$validColors),
        ]);

        Type::create($request->all());

        return redirect()->route('type.index')->with('global', '攤位類型已新增');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        return view('type.create-or-edit', compact('type'));
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
        $this->validate($request, [
            'name'   => 'required',
            'target' => 'integer|min:0',
            'color'  => 'required|in:' . implode(',', ColorTagTrait::$validColors),
        ]);

        $type->update($request->all());

        return redirect()->route('type.index')->with('global', '攤位類型已更新');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Type $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();

        return redirect()->route('type.index')->with('global', '攤位類型已刪除');
    }
}
