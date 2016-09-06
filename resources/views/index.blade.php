@extends('app')

@section('title', '首頁')

@section('css')
    <style>
        .ui.segment.normal {
            background: none;
            box-shadow: none;
            border: none;
            border-radius: 0;
            margin-top: inherit;
            margin-bottom: inherit;
        }

        .jumbotron {
            text-align: center;
            background: rgba(100, 100, 100, .6);
            margin-top: 20vh;
            margin-bottom: 20vh;
            padding-top: 40px;
            border-radius: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="jumbotron">
        <div class="ui translucent vertical center aligned segment">
            <h1 class="ui inverted center aligned icon header">
                <i class="circular inverted red massive marker icon"></i>
                <nobr>逢甲大學</nobr>
                <nobr>學生社團博覽會</nobr>
                <p class="ui sub header">
                    打卡集點抽獎
                </p>
            </h1>
            <a href="{{ route('map.index') }}" class="ui large inverted red center aligned button"
               style="margin-top: 5vh;">
                GO!
            </a>
        </div>
    </div>
@endsection
