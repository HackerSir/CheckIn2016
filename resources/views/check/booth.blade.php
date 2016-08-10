@extends('app')

@section('title', "{$booth->name} - 打卡集點")

@section('content')
    <h1 class="ui teal header center aligned">
        {{ $booth->name }}
    </h1>
    @include('booth.info', $booth)
    <div style="text-align: center; padding: 20px">
        {!! Form::open(['route' => ['check.booth', $booth->code], 'style' => 'display: inline']) !!}
        <button type="submit" id="btnSubmit" class="ui massive green icon button loading" disabled>
            <i class="check icon"></i> 打卡集點
        </button>
        {!! Form::close() !!}
    </div>

@endsection

@section('js')
    <script>
        $(function () {
            setTimeout(function () {
                var $btnSubmit = $('#btnSubmit');
                $btnSubmit.removeClass('loading');
                $btnSubmit.removeAttr('disabled');
            }, 5000);
        });
    </script>
@endsection
