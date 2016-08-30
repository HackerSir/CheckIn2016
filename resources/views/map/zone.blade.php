@extends('app')

@section('title', "ZONE {$zone} - 地圖")

@section('css')
    <style>
        img {
            max-height: 800px;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui teal header center aligned">
        ZONE {{ $zone }} - 地圖
    </h2>
    <div>
        <a href="{{ route('map.index') }}" class="ui blue inverted icon button">
            <i class="left arrow icon"></i> 返回
        </a>
        <button class="ui red inverted labeled icon button" id="zoom-btn">
            <i class="zoom icon"></i> 放大
        </button>
        <br/>
        <a href="{{ $imgUrl }}" class="ui blue inverted icon button" target="_blank">
            <i class="external icon"></i> 新頁面開啟
        </a>
        <a href="{{ $imgUrl }}" class="ui brown inverted icon button" download="zone_{{ $zone }}">
            <i class="download icon"></i> 下載
        </a>
    </div>
    <div style="overflow: auto">
        <img src="{{ $imgUrl }}" alt="地圖" class="ui image" id="map">
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#zoom-btn').click(function () {
                $('#map').toggleClass('image');
            });
        });
    </script>
@endsection
