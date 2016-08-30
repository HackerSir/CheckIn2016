@extends('app')

@section('title', !empty($zone) ? "Zone {$zone} - ":''.'地圖')

@section('css')
    <style>
        img {
            max-height: 800px;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui teal header center aligned">
        @if(!empty($zone))
            Zone {{$zone}} -
        @endif
        地圖
    </h2>
    <div class="ui basic buttons">
        <a href="{{ route('map.index') }}" class="ui icon button @if(empty($zone))active @endif">
            <i class="map outline icon"></i> 全圖
        </a>
        <a href="{{ route('map.index', 'A') }}" class="ui icon button @if($zone == 'A')active @endif">
            <i class="map outline icon"></i> A
        </a>
        <a href="{{ route('map.index', 'B') }}" class="ui icon button @if($zone == 'B')active @endif">
            <i class="map outline icon"></i> B
        </a>
        <a href="{{ route('map.index', 'C') }}" class="ui icon button @if($zone == 'C')active @endif">
            <i class="map outline icon"></i> C
        </a>
    </div>
    <div>
        <button class="ui red inverted labeled icon button" id="zoom-btn">
            <i class="zoom icon"></i> 放大
        </button>
        <br/>
        <a href="{{ $imgUrl }}" class="ui blue inverted icon button" target="_blank">
            <i class="external icon"></i> 新頁面開啟
        </a>
        <a href="{{ $imgUrl }}" class="ui brown inverted icon button" download="map">
            <i class="download icon"></i> 下載
        </a>
    </div>
    <div style="overflow: auto">
        @if(empty($zone))
            <img src="{{ $thumbnailUrl }}" alt="地圖" class="ui image" id="map" usemap="#linkMap">
            <map name="linkMap">
                <area shape="rect" coords="344,467,36,246" href="{{ route('map.index', 'A') }}">
                <area shape="rect" coords="354,247,610,464" href="{{ route('map.index', 'B') }}">
                <area shape="rect" coords="117,476,585,724" href="{{ route('map.index', 'C') }}">
            </map>
        @else
            <img src="{{ $thumbnailUrl }}" alt="地圖" class="ui image" id="map">
        @endif
    </div>
@endsection

@section('js')
    @if(empty($zone))
        {{-- 令ImageMap超連結支援相對座標，來源：https://github.com/clarketm/image-map --}}
        {!! Html::script('js/image-map.min.js') !!}
        <script>
            $(function () {
                var $map = $('#map');
                $map.imageMap();
                $('#zoom-btn').click(function () {
                    $map.toggleClass('image');
                    $map.imageMap();
                });
            });
        </script>
    @else
        <script>
            $(function () {
                var $map = $('#map');
                $('#zoom-btn').click(function () {
                    $map.toggleClass('image');
                });
            });
        </script>
    @endif

@endsection
