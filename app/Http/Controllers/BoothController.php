<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Services\FileService;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;

class BoothController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * TypeController constructor.
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->middleware('permission:booth.manage', [
            'except' => [
                'index',
                'show',
                'anyData',
            ],
        ]);
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('booth.index');
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

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $dataTables = Datatables::of(Booth::with('type'))
            ->filterColumn('type_id', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                $query->whereIn('type_id', function ($query) use ($keyword) {
                    $query->select('types.id')
                        ->from('types')
                        ->join('booths', 'types.id', '=', 'booths.type_id')
                        ->whereRaw('types.name LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->make(true);

        return $dataTables;
    }

    public function updateCode(Booth $booth, Request $request)
    {
        $booth->update([
            'code' => str_random(20),
        ]);

        return redirect()->route('booth.show', $booth)->with('global', 'CODE已更新，並重新建立QR碼');
    }

    public function downloadQRCode(Booth $booth = null)
    {
        //是否指定單一攤位
        $isSpecificBooth = !empty($booth->id);
        //指定的攤位（若無指定，則為所有攤位）
        $booths = ($isSpecificBooth) ? $booth : Booth::all();
        //檔名
        $fileName = 'QRCode_' . (($isSpecificBooth) ? $booth->name : 'All') . '.docx';

        //建立檔案
        $phpWord = $this->fileService->generateQRCodeDocFile($booths);

        //=========================================================================
        //輸出檔案
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        //設定路徑（PHP暫存路徑）
        $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'word_' . Carbon::now()->getTimestamp() . '.docx';
        //建立暫存檔案
        $objWriter->save($filePath);

        //下載檔案
        return response()->download($filePath, $fileName);
    }
}
