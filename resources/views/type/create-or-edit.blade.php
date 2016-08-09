@extends('app')

@php($isEditMode = isset($type))
@php($methodText = $isEditMode ? '編輯' : '新增')

@section('title', $methodText . '攤位類型')

@section('content')
    <h2 class="ui teal header center aligned">
        {{ $methodText }}攤位類型
    </h2>
    @if($isEditMode)
        {!! SemanticForm::open()->action(route('type.update', $type))->patch() !!}
        {!! SemanticForm::bind($type) !!}
    @else
        {!! SemanticForm::open()->action(route('type.store')) !!}
    @endif
    <div class="ui stacked segment">
        {!! SemanticForm::text('name')->label('攤位類型名稱')->placeholder('如：學藝性')->required() !!}
        {!! SemanticForm::text('target')->label('目標攤位數量')->placeholder('過關需求該類型攤位數量，預設為0，表示無要求數量') !!}
        @include('form.field.color', ['errors' => $errors, 'model' => isset($type) ? $type : null])
        <div style="text-align: center">
            <a href="{{ route('type.index') }}" class="ui blue inverted icon button">
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
