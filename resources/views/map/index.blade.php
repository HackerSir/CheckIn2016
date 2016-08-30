@extends('app')

@section('title', '地圖')

@section('css')
    <style>
        img {
            max-height: 800px;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui teal header center aligned">
        地圖
    </h2>
    <div>
        <button class="ui red inverted labeled icon button" id="zoom-btn">
            <i class="zoom icon"></i> 放大
        </button>
        <br/>
        <a href="{{ asset('img/map/map.jpg') }}" class="ui blue inverted icon button" target="_blank">
            <i class="external icon"></i> 新頁面開啟
        </a>
        <a href="{{ asset('img/map/map.jpg') }}" class="ui brown inverted icon button" download="map">
            <i class="download icon"></i> 下載
        </a>
    </div>
    <div style="overflow: auto">
        <img src="{{ asset('img/map/map.jpg') }}" alt="地圖" class="ui image" id="map">
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
