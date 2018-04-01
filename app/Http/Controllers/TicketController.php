<?php

namespace App\Http\Controllers;

use App\Ticket;
use Datatables;
use App\Services\LogService;

class TicketController extends Controller
{
    /**
     * @var LogService
     */
    private $logService;

    /**
     * TicketController constructor.
     * @param LogService $logService
     */
    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ticket.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //Log
        $student = $ticket->student;
        $operator = auth()->user();
        $this->logService->info("[Ticket][Delete] {$operator->name} 移除了 {$student->displayName} 的抽獎券", [
            'ip'       => request()->ip(),
            'operator' => $operator,
            'student'  => $student,
        ]);

        $ticket->delete();

        return redirect()->route('ticket.index')->with('global', '抽獎券已刪除');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $dataTables = Datatables::of(Ticket::with('student'))
            ->filterColumn('student_nid', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                $query->whereIn('student_nid', function ($query) use ($keyword) {
                    $query->select('students.nid')
                        ->from('students')
                        ->join('tickets', 'students.nid', '=', 'tickets.student_nid')
                        ->whereRaw('students.name LIKE ?', ['%' . $keyword . '%'])
                        ->orWhereRaw('students.nid LIKE ?', ['%' . strtoupper($keyword) . '%']);
                });
            })
            ->make(true);

        return $dataTables;
    }

    public function ticket()
    {
        return view('ticket.ticket');
    }

    public function ticketInfo()
    {
        $id = \Request::get('id');
        $ticket = Ticket::find($id);
        if (!$ticket) {
            $json = [
                'success' => false,
                'id'      => $id,
            ];

            return response()->json($json);
        }
        $json = [
            'success' => true,
            'id'      => $ticket->id,
            'name'    => $ticket->student->name,
            'class'   => $ticket->student->class,
        ];

        return response()->json($json);
    }
}
