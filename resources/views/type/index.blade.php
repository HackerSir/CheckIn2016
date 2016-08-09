@extends('app')

@section('title', '攤位類型')

@section('content')
    <h2 class="ui teal header center aligned">
        攤位類型
    </h2>
    <a href="{{ route('type.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增攤位類型
    </a>
    <table class="ui selectable celled padded unstackable table">
        <thead>
        <tr>
            <th>名稱</th>
            <th>標籤</th>
            <th>攤位數量</th>
            <th>目標攤位數量</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
            <tr>
                <td>
                    {{ $type->name }}
                </td>
                <td>
                    {!! $type->tag !!}
                </td>
                <td>
                    {{ $type->booths->count() }}
                </td>
                <td>
                    {{ $type->target }}
                </td>
                <td>
                    <a href="{{ route('type.edit', $type) }}" class="ui icon brown inverted button" title="編輯類型">
                        <i class="edit icon"></i>
                    </a>
                    {!! Form::open(['route' => ['type.destroy', $type], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此類型嗎？');"]) !!}
                    <button type="submit" class="ui icon red inverted button" title="刪除類型">
                        <i class="trash icon"></i>
                    </button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
