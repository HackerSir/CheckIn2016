<?php

namespace App\Http\Controllers;

use App\Ticket;
use Datatables;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::paginate();

        return view('ticket.index', compact('tickets'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
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
        $dataTables = Datatables::of(Ticket::with('user'))
            ->filterColumn('user_id', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                $query->whereIn('user_id', function ($query) use ($keyword) {
                    $query->select('users.id')
                        ->from('users')
                        ->join('tickets', 'users.id', '=', 'tickets.user_id')
                        ->whereRaw('users.name LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->make(true);

        return $dataTables;
    }
}
