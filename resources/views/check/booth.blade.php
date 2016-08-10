@extends('app')

@section('title', "{$booth->name} - 打卡集點")

@section('content')
    <h1 class="ui teal header center aligned">
        {{ $booth->name }}
    </h1>
    <div class="ui center aligned">
        @if($booth->image)
            <img src="{{ $booth->image  }}" class="ui big rounded centered image" style="max-width: 100%"/>
        @endif
    </div>
    <div class="ui segment">
        <span class="ui {{ $booth->type->color }} ribbon label">{{ $booth->type->name }}</span>
        <span class="ui header">{{ $booth->name }}</span>
        <p style="margin-top: 1em">
            {!! $booth->displayDescription !!}
        </p>
        @if($booth->url)
            <a href="{{ $booth->url }}" target="_blank"><i class="linkify icon"></i> {{ $booth->url }}</a>
        @endif
    </div>

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
