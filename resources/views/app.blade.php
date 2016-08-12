<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    {{-- Metatag --}}
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>@yield('title') - {{ config('site.name') }}</title>

    {{-- CSS --}}
    {!! Html::style('semantic/semantic.min.css') !!}
    {!! Html::style('https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css') !!}
    {!! Html::style('https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css') !!}
    {!! Html::style('//cdn.jsdelivr.net/alertifyjs/1.8.0/css/alertify.min.css') !!}
    {!! Html::style('//cdn.jsdelivr.net/alertifyjs/1.8.0/css/themes/semantic.min.css') !!}
    {!! Html::style('css/sticky-footer.css') !!}
    <style>
        body {
            padding-top: 70px;
            height: auto;
            background: url("{{ asset('img/background/index.jpg') }}") no-repeat fixed center;
        }

        .secondary.pointing.menu {
            border: none !important;
        }

        .secondary.pointing.menu .toc.item {
            display: none;
        }

        @media only screen and (max-width: 700px) {
            .secondary.pointing.menu .item,
            .secondary.pointing.menu .menu {
                display: none;
            }

            .secondary.pointing.menu .toc.item {
                display: block;
            }
        }
    </style>
    @yield('css')
</head>
<body>
{{-- Navbar --}}
@include('navbar.menu')

{{-- Content --}}
@if(Request::is('/'))
    <div class="ui container">
        @yield('content')
    </div>
@else
    <div class="ui container segment" style="background-color: rgba(255,255,255,0.8)">
        @yield('content')
    </div>
@endif

{{-- Footer --}}
<div class="ui inverted center aligned segment footer">
    <div class="ui container center aligned">
        <p>
            Copyright (c) 2016 Feng Chia University Hackers' Club (a.k.a. HackerSir), All rights reserved.
        </p>
    </div>
</div>

{{-- Javascript --}}
{!! Html::script('//code.jquery.com/jquery-3.1.0.min.js') !!}
{!! Html::script('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js') !!}
{!! Html::script('https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js') !!}
{!! Html::script('https://cdn.datatables.net/1.10.12/js/dataTables.semanticui.min.js') !!}
{!! Html::script('semantic/semantic.js') !!}
{!! Html::script('//cdn.jsdelivr.net/alertifyjs/1.8.0/alertify.min.js') !!}
<script>
    $(document).ready(function () {
//            $('.toc.item').click(function () {
//                $('i.sidebar.icon').transition('fade out');
//            });
//            $('.ui.sidebar').sidebar('attach events', '.toc.item').sidebar('setting', 'transition', 'overlay');
        $('.ui.dropdown').each(function () {
            $(this).dropdown({
                fullTextSearch: true
            });
        });

        //AlertifyJS
        alertify.defaults = {
            notifier: {
                position: 'top-right'
            }
        };
        @if(Session::has('global'))
            alertify.notify('{{ Session::get('global') }}', 'success', 5);
        @endif
        @if(Session::has('warning'))
            alertify.notify('{{ Session::get('warning') }}', 'warning', 5);
        @endif
        // popup
        $('[title]').each(function () {
            $(this).popup({
                variation: 'inverted',
                position: 'right center'
            });
        });

        // DataTable 預設設定
        $.extend(true, $.fn.dataTable.defaults, {
            processing: true,
            serverSide: true,
            pageLength: 50,
            oLanguage: {
                sProcessing: '處理中...',
                sLengthMenu: '顯示 _MENU_ 項結果',
                sZeroRecords: '沒有匹配結果',
                sInfo: '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
                sInfoEmpty: '顯示第 0 至 0 項結果，共 0 項',
                sInfoFiltered: '（從 _MAX_ 項結果過濾）',
                sSearch: '搜索：',
                oPaginate: {
                    sFirst: '第一頁',
                    sPrevious: '上一頁',
                    sNext: '下一頁',
                    sLast: '最後一頁'
                }
            }
        });
    });
</script>
@yield('js')

</body>
</html>
