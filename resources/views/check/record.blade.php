@extends('app')

@section('title', '打卡記錄 - 打卡集點')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡記錄
    </h2>
    <div>
        <a href="{{ route('check.index') }}" class="ui left labeled icon blue inverted button">
            <i class="arrow left icon"></i>
            打卡進度
        </a>
    </div>
    {{-- 打卡集點記錄 --}}
    <div class="ui segment">
        <span class="ui blue ribbon label">打卡記錄（每個攤位僅顯示一次）</span>
        <div class="ui relaxed divided list">
            @forelse($points as $point)
                <div class="item">
                    <i class="large marker middle aligned icon"></i>
                    <div class="content">
                        <a href="{{ route('booth.show', $point->booth) }}" class="header" target="_blank">
                            {!! $point->booth->type->tag or '' !!} {{ $point->booth->name }}
                        </a>
                        <div class="description" style="margin-top: .2em">
                            @if($point->check_at)
                                {{ $point->check_at }}（{{ $point->check_at->diffForHumans() }}）
                            @else
                                無時間記錄
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                暫無打卡集點記錄
            @endforelse
        </div>
    </div>
    <div class="ui center aligned attached segment" style="border: none; background: none">
        {!! (new Landish\Pagination\SemanticUI($points))->render() !!}
    </div>
@endsection
