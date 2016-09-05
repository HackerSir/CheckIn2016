@extends('app')

@section('title', '抽獎券')

@section('css')
    <style>
        h1.ui.header {
            font-size: 72px;
        }
        .ui.segment.normal {
            background: none;
            box-shadow: none;
            border: none;
            border-radius: 0;
        }
    </style>
@endsection

@section('content')
    <h1 class="ui teal header center aligned">
        抽獎券
    </h1>
    <div class="ui segment">

    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#ticket_search_form').submit(function () {
                return false;
            });
        });
    </script>
@endsection
