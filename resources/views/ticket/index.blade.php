@extends('app')

@section('title', '抽獎券')

@section('content')
    <h2 class="ui teal header center aligned">
        抽獎券
    </h2>
    <table class="ui selectable celled padded unstackable table">
        <thead>
        <tr>
            <th>#</th>
            <th>使用者</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>
                    {{ $ticket->id }}
                </td>
                <td>
                    {{ link_to_route('user.show', $ticket->user->name, $ticket->user, ['target' => '_blank']) }}
                </td>
                <td>
                    {!! Form::open(['route' => ['ticket.destroy', $ticket], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此抽獎券嗎？');"]) !!}
                    <button type="submit" class="ui icon red inverted button" title="刪除抽獎券">
                        <i class="trash icon"></i>
                    </button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="ui center aligned attached segment" style="border: none">
        {!! (new Landish\Pagination\SemanticUI($tickets))->render() !!}
    </div>
@endsection
