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
        {!! SemanticForm::text('number')->label('攤位編號')->placeholder('如：C9') !!}
        @include('form.field.type_id', ['errors' => $errors, 'type_id' => $isEditMode ? $booth->type_id : null])
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
