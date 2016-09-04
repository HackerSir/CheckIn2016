@extends('app')

@section('title', "{$booth->name} - 攤位{$booth->number}")

@section('content')
    <h2 class="ui teal header center aligned">
        {{ $booth->name }} - 攤位{{ $booth->number }}
    </h2>
    @include('booth.info', $booth)

    @if(Entrust::can('booth.manage'))
        <div class="ui segments">
            <div class="ui center aligned segment">
                <span class="ui header">管理限定</span>
            </div>
            <div class="ui segment">
                <span class="ui orange ribbon label">CODE</span>
                <div>
                    {{ $booth->code }}
                    {!! Form::open(['route' => ['booth.updateCode', $booth], 'style' => 'display: inline', 'onSubmit' => "return confirm('這會使原本的QR碼失效，確定更新CODE嗎？');"]) !!}
                    <button type="submit" class="ui icon red inverted button" title="更新CODE（會使原本的QR碼失效）">
                        <i class="refresh icon"></i>
                    </button>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="ui segment">
                <span class="ui blue ribbon label">打卡網址</span>
                <div>
                    <nobr>
                        <a href="{{ route('check.booth', $booth->code) }}" target="_blank">
                            <i class="linkify icon"></i> {{ route('check.booth', $booth->code) }}
                        </a>
                    </nobr>
                </div>
            </div>
            <div class="ui segment">
                <span class="ui blue ribbon label">打卡QR碼</span>
                <a href="{{ route('booth.downloadQRCode', $booth) }}" class="ui blue inverted labeled icon button">
                    <i class="file word outline icon"></i>
                    下載QR碼
                </a>
                <div style="max-width: 450px">
                    <a href="{{ $booth->QR }}" target="_blank">
                        <img src="{{ $booth->QR }}" class="ui large image">
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div style="text-align: center">
        <a href="{{ route('booth.index') }}" class="ui blue inverted icon button">
            <i class="arrow left icon"></i> 攤位清單
        </a>
        @if(Entrust::can('booth.manage'))
            <a href="{{ route('booth.edit', $booth) }}" class="ui brown inverted icon button">
                <i class="edit icon"></i> 編輯攤位
            </a>
            {!! Form::open(['route' => ['booth.destroy', $booth], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此攤位嗎？');"]) !!}
            <button type="submit" class="ui icon red inverted button">
                <i class="trash icon"></i> 刪除攤位
            </button>
            {!! Form::close() !!}
        @endif
    </div>

@endsection
