@extends('app')

@section('title', "{$booth->name} - 攤位")

@section('css')
    <style>
        /* TODO: 看要置左還是置中，然後看要不要抽出來做*/
        @media only screen and (max-width: 767px) {
            td {
                text-align: center !important;
            }
        }

        #gravatar {
            border: 3px solid white;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        #gravatar:hover {
            border: 3px dotted black;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui teal header center aligned">
        {{ $booth->name }} - 攤位
    </h2>
    <div class="ui center aligned">
        @if($booth->image)
            <img src="{{ $booth->image  }}" class="ui big rounded centered image" style="max-width: 100%"/>
        @endif
    </div>

    <table class="ui selectable stackable table">
        <tr>
            <td class="four wide right aligned">類型：</td>
            <td>{!! $booth->type->tag or '' !!}</td>
        </tr>
        <tr>
            <td class="four wide right aligned">名稱：</td>
            <td>{{ $booth->name }}</td>
        </tr>
        <tr>
            <td class="four wide right aligned">簡介：</td>
            <td>{!! $booth->displayDescription !!}</td>
        </tr>
        <tr>
            <td class="four wide right aligned">網址：</td>
            <td>
                @if($booth->url)
                    <a href="{{ $booth->url }}" target="_blank"><i class="linkify icon"></i> {{ $booth->url }}</a>
                @endif
            </td>
        </tr>
    </table>

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
