@foreach($items as $item)
    @if($item->hasChildren())
        {{-- 巢狀次級選單 --}}
        {{-- FIXME: 不支援巢狀 --}}
    @else
        {{-- 一般項目 --}}
        @if($item->link)
            {{-- 超連結 --}}
            {{-- FIXME: @lm-attrs 無法同時處理兩種情況
            自動判斷active：@lm-attrs($item->link)
            令LaravelMenu可直接調整或追加屬性：@lm-attrs($item)
            --}}
            <a @lm-attrs($item->link) class="item" @lm-endattrs href="{!! $item->url() !!}">
                @if($item->icon)
                    <i class="{{ $item->icon }} icon"></i>
                @endif
                {!! $item->title !!}
            </a>
        @else
            {{-- 文字 --}}
            {!! $item->title !!}
        @endif
    @endif

    @if($item->divider)
        {{-- 分隔線 --}}
        {{-- TODO: 最上層無法顯示分隔線 --}}
        <div{!! Lavary\Menu\Builder::attributes($item->divider) !!}></div>
    @endif
@endforeach

{{--
原始的view
        @foreach($items as $item)
            <li@lm-attrs($item) @if($item->hasChildren())class ="dropdown"@endif @lm-endattrs>
            @if($item->link)
                <a@lm-attrs($item->link) @if($item->hasChildren()) class="dropdown-toggle" data-toggle="dropdown" @endif @lm-endattrs href="{!! $item->url() !!}">
            {!! $item->title !!}
            @if($item->hasChildren()) <b class="caret"></b> @endif
            </a>
            @else
                {!! $item->title !!}
            @endif
            @if($item->hasChildren())
                <ul class="dropdown-menu">
                    @include(config('laravel-menu.views.bootstrap-items'),
            array('items' => $item->children()))
                </ul>
                @endif
                </li>
                @if($item->divider)
                    <li{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
                @endif
                @endforeach
--}}
