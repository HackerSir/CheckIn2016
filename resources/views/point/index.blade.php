@extends('app')

@section('title', '打卡集點記錄')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡集點記錄
    </h2>
    <a href="{{ route('point.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增打卡集點記錄
    </a>
    <table class="ui selectable celled padded unstackable table">
        <thead>
        <tr>
            <th>#</th>
            <th>打卡集點者</th>
            <th>攤位</th>
            <th>打卡集點時間</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points as $point)
            <tr>
                <td>
                    {{ $point->id }}
                </td>
                <td>
                    {{ link_to_route('user.show', $point->user->name, $point->user, ['target' => '_blank']) }}
                </td>
                <td>
                    {{ link_to_route('booth.show', $point->booth->name, $point->booth, ['target' => '_blank']) }}
                </td>
                <td>
                    {{ $point->check_at }}
                </td>
                <td>
                    {!! Form::open(['route' => ['point.destroy', $point], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此打卡集點記錄嗎？');"]) !!}
                    <button type="submit" class="ui icon red inverted button" title="刪除打卡集點記錄">
                        <i class="trash icon"></i>
                    </button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="ui center aligned attached segment" style="border: none">
        {!! (new Landish\Pagination\SemanticUI($points))->render() !!}
    </div>
@endsection
