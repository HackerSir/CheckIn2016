@extends('app')

@section('title', '攤位')

@section('content')
    <h2 class="ui teal header center aligned">
        攤位
    </h2>
    @if(Entrust::can('booth.manage'))
        <a href="{{ route('booth.create') }}" class="ui icon brown inverted button">
            <i class="plus icon" aria-hidden="true"></i> 新增攤位
        </a>
    @endif
    <table class="ui selectable celled padded unstackable table">
        <thead>
        <tr>
            <th>類型</th>
            <th>名稱</th>
            <th>圖片</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($booths as $booth)
            <tr>
                <td>
                    {!! $booth->type->tag or '' !!}
                </td>
                <td>
                    {{ $booth->name }}
                </td>
                <td>
                    @if($booth->image)
                        <img src="{{ $booth->image  }}" class="ui small centered rounded image"/>
                    @endif
                </td>
                <td>
                    <a href="{{ route('booth.show', $booth) }}" class="ui icon blue inverted button" title="攤位資料">
                        <i class="search icon"></i>
                    </a>
                    @if(Entrust::can('booth.manage'))
                        <a href="{{ route('booth.edit', $booth) }}" class="ui icon brown inverted button" title="編輯攤位">
                            <i class="edit icon"></i>
                        </a>
                        {!! Form::open(['route' => ['booth.destroy', $booth], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此攤位嗎？');"]) !!}
                        <button type="submit" class="ui icon red inverted button" title="刪除攤位">
                            <i class="trash icon"></i>
                        </button>
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="ui center aligned attached segment" style="border: none">
        {!! (new Landish\Pagination\SemanticUI($booths))->render() !!}
    </div>
@endsection
