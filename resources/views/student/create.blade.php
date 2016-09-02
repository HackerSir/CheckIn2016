@extends('app')

@section('title', '新增學生')

@section('content')
    <h2 class="ui teal header center aligned">
        新增學生
    </h2>
    {!! SemanticForm::open()->action(route('student.store')) !!}
    <div class="ui stacked segment">
        {!! SemanticForm::text('nid')->label('NID')->required() !!}
        <div style="text-align: center">
            <a href="{{ route('student.index') }}" class="ui blue inverted icon button">
                <i class="icon arrow left"></i> 返回列表
            </a>
            {!! SemanticForm::submit('<i class="checkmark icon"></i> 確認')->addClass('ui icon submit red inverted button') !!}
        </div>
    </div>
    @if($errors->count())
        <div class="ui error message" style="display: block">
            <ul class="list">
                @foreach($errors->all('<li>:message</li>') as $error)
                    {!! $error !!}
                @endforeach
            </ul>
        </div>
    @endif
    {!! SemanticForm::close() !!}
@endsection
