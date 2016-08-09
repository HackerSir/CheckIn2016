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
        <div class="field required{{ ($errors->has('color'))?' error':'' }}">
            <label>標籤顏色</label>
            <div class="ui fluid selection dropdown">
                @if($isEditMode)
                    <input type="hidden" name="color" value="{{ $type->color }}">
                @else
                    <input type="hidden" name="color">
                @endif
                <i class="dropdown icon"></i>
                <div class="default text">
                    <span class="ui tag label single line" style="margin-left: 10px">請選擇標籤顏色</span>
                </div>
                <div class="menu">
                    @foreach(\App\Role::$validColors as $color)
                        <div class="item" data-value="{{ $color }}">
                            <span class="ui tag label single line {{ $color }}"
                                  style="margin-left: 10px">{{ $color }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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
