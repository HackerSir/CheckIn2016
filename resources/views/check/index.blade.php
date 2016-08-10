@extends('app')

@section('title', '打卡集點')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡集點
    </h2>
    {{-- TODO: 抽獎券 --}}
    {{-- TODO: 進度 --}}
    {{-- 打卡集點記錄 --}}
    <div class="ui segment">
        <span class="ui blue ribbon label">最近5筆打卡集點記錄</span>
        <div class="ui relaxed divided list">
            @forelse($lastPoints as $point)
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
@endsection
