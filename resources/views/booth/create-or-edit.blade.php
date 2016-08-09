@extends('app')

@php($isEditMode = isset($booth))
@php($methodText = $isEditMode ? '編輯' : '新增')

@section('title', $methodText . '攤位')

@section('content')
    <h2 class="ui teal header center aligned">
        {{ $methodText }}攤位
    </h2>
    @if($isEditMode)
        {!! SemanticForm::open()->action(route('booth.update', $booth))->patch() !!}
        {!! SemanticForm::bind($booth) !!}
    @else
        {!! SemanticForm::open()->action(route('booth.store')) !!}
    @endif
    <div class="ui stacked segment">
        <div class="field{{ ($errors->has('type_id'))?' error':'' }}">
            <label>攤位類型</label>
            <div class="ui fluid selection dropdown">
                @if($isEditMode)
                    <input type="hidden" name="type_id" value="{{ $booth->type_id }}">
                @elseif(Request::old('type_id'))
                    <input type="hidden" name="type_id" value="{{ Request::old('type_id') }}">
                @else
                    <input type="hidden" name="type_id">
                @endif
                <i class="dropdown icon"></i>
                <div class="default text">
                    <span class="ui tag label single line" style="margin-left: 10px">請選擇攤位類型</span>
                </div>
                <div class="menu">
                    <div class="item" data-value="">
                        <span class="ui tag label single line" style="margin-left: 10px">請選擇攤位類型</span>
                    </div>
                    @foreach(\App\Type::all() as $booth)
                        <div class="item" data-value="{{ $booth->id }}">
                            <span class="ui tag label single line {{ $booth->color }}" style="margin-left: 10px">{{ $booth->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {!! SemanticForm::text('name')->label('攤位名稱')->placeholder('如：黑客社')->required() !!}
        {!! SemanticForm::textarea('description')->label('攤位簡介')->placeholder('如：黑客社是由一群喜歡資訊安全的人們所創立，因緣際會之下成立了這個社團。') !!}
        {!! SemanticForm::text('url')->label('網址')->placeholder('如：https://hackersir.org/') !!}
        {!! SemanticForm::text('image')->label('圖片網址')->placeholder('如：http://i.imgur.com/ijlqQ2a.jpg') !!}
        <div style="text-align: center">
            <a href="{{ route('booth.index') }}" class="ui blue inverted icon button">
                <i class="icon arrow left"></i> 返回列表
            </a>
            @if($isEditMode)
                <a href="{{ route('booth.show', $booth) }}" class="ui blue inverted icon button">
                    <i class="icon arrow left"></i> 返回攤位
                </a>
            @endif
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
