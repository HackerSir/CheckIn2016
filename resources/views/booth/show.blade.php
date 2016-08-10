@extends('app')

@section('title', "{$booth->name} - 攤位")

@section('content')
    <h2 class="ui teal header center aligned">
        {{ $booth->name }} - 攤位
    </h2>
    @include('booth.info', $booth)

    @if(Entrust::can('booth.manage'))
        <table class="ui selectable stackable table">
            <thead>
            <tr>
                <th colspan="2" class="center aligned">管理限定</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="four wide right aligned">CODE：</td>
                <td>
                    {{ $booth->code }}
                    {!! Form::open(['route' => ['booth.updateCode', $booth], 'style' => 'display: inline', 'onSubmit' => "return confirm('這會使原本的QR碼失效，確定更新CODE嗎？');"]) !!}
                    <button type="submit" class="ui icon red inverted button" title="更新CODE（會使原本的QR碼失效）">
                        <i class="refresh icon"></i>
                    </button>
                    {!! Form::close() !!}
                </td>
            </tr>
            <tr>
                <td class="four wide right aligned">打卡網址：</td>
                <td>
                    <a href="{{ route('check.booth', $booth->code) }}" target="_blank">
                        <i class="linkify icon"></i> {{ route('check.booth', $booth->code) }}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="four wide right aligned">打卡QR碼：</td>
                <td>
                    <a href="{{ $booth->QR }}" target="_blank">
                        <img src="{{ $booth->QR }}">
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
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
