<div class="ui center aligned">
    @if($booth->image)
        <img src="{{ $booth->image  }}" class="ui big rounded centered image" style="max-width: 100%"/>
    @endif
</div>
<div class="ui segment">
    @if($booth->type)
        <span class="ui {{ $booth->type->color }} ribbon label">{{ $booth->type->name }}</span>
    @endif
    <span class="ui header">{{ $booth->name }}</span>
    <p style="margin-top: 1em">
        {!! $booth->displayDescription !!}
    </p>
    @if($booth->url)
        <a href="{{ $booth->url }}" target="_blank"><i class="linkify icon"></i> {{ $booth->url }}</a>
    @endif
</div>
