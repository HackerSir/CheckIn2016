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
        $this->middleware('permission:booth.manage', [
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
        $booths = Booth::with('type')->paginate();

        return view('booth.index', compact('booths'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('booth.create-or-edit');
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
            'type_id' => 'exists:types,id',
            'name'    => 'required',
            'url'     => 'url',
            'image'   => 'url',
        ]);

        $booth = Booth::create(array_merge($request->all(), [
            'type_id' => $request->get('type_id') ?: null,
            'code'    => str_random(20),
        ]));

        return redirect()->route('booth.show', $booth)->with('global', '攤位已新增');
    }

    /**
     * Display the specified resource.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function show(Booth $booth)
    {
        return view('booth.show', compact('booth'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function edit(Booth $booth)
    {
        return view('booth.create-or-edit', compact('booth'));
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
        $this->validate($request, [
            'type_id' => 'exists:types,id',
            'name'    => 'required',
            'url'     => 'url',
            'image'   => 'url',
        ]);

        $booth->update(array_merge($request->all(), [
            'type_id' => $request->get('type_id') ?: null,
        ]));

        return redirect()->route('booth.show', $booth)->with('global', '攤位已更新');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Booth $booth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booth $booth)
    {
        $booth->delete();

        return redirect()->route('booth.index')->with('global', '攤位已刪除');
    }

    public function updateCode(Booth $booth, Request $request)
    {
        $booth->update([
            'code' => str_random(20),
        ]);

        return redirect()->route('booth.show', $booth)->with('global', 'CODE已更新，並重新建立QR碼');
    }
}
