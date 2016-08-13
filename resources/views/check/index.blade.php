@extends('app')

@section('title', '打卡集點')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡集點
    </h2>
    <div class="ui segment">
        <span class="ui brown ribbon label">使用者</span>
        {{ auth()->user()->name }}
    </div>
    {{-- 抽獎券 --}}
    @if(auth()->user()->ticket)
        <div class="ui segment">
            <span class="ui green ribbon label">抽獎編號</span>
            {{ auth()->user()->ticket->id }}
        </div>
    @endif
    {{-- 進度 --}}
    <div class="ui segment">
        <span class="ui teal ribbon label">進度</span>
        <p>達成所有目標後，即可取得抽獎編號</p>
        <div class="ui grid">
            <div class="two column row">
                <div class="column"><span class="ui tag label single line">所有攤位</span></div>
                <div class="column">
                    @if($progress['total']['target'] > 0)
                        <div class="ui indicating progress" data-value="{{ $progress['total']['now'] }}"
                             data-total="{{ $progress['total']['target'] }}">
                            <div class="bar">
                                <div class="progress"></div>
                            </div>
                            <div class="label">{{ $progress['total']['now'] }}
                                / {{ $progress['total']['target'] }}</div>
                        </div>
                    @else
                        {{ $progress['total']['now'] }} / 無目標
                    @endif
                </div>
            </div>
            @foreach($types as $type)
                <div class="two column row">
                    <div class="column">{!! $type->tag !!}</div>
                    <div class="column">
                        @if($type->target > 0)
                            <div class="ui indicating progress" data-value="{{ $progress[$type->id]['now'] }}"
                                 data-total="{{ $progress[$type->id]['target'] }}">
                                <div class="bar">
                                    <div class="progress"></div>
                                </div>
                                <div class="label">{{ $progress[$type->id]['now'] }}
                                    / {{ $progress[$type->id]['target'] }}</div>
                            </div>
                        @else
                            {{ $progress[$type->id]['now'] }} / 無目標
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
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

@section('js')
    <script>
        $(function () {
            $('.ui.progress').each(function () {
                $(this).progress();
            });
        });
    </script>
@endsection
