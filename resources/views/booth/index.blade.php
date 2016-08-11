@extends('app')

@section('title', '攤位')

@section('content')
    <h2 class="ui teal header center aligned">
        攤位
    </h2>
    @if(Entrust::can('booth.manage'))
        <a href="{{ route('booth.create') }}" class="ui icon brown inverted button">
            <i class="plus icon" aria-hidden="true"></i> 新增攤位
        </a>
    @endif
    <table class="ui selectable celled padded unstackable table" id="booth-table">
        <thead>
        <tr>
            <th>#</th>
            <th>類型</th>
            <th>名稱</th>
            <th>圖片</th>
            <th>操作</th>
        </tr>
        </thead>
    </table>
@endsection

@section('js')
    <script>
        var types;
        $(function () {
            types = {!! \App\Type::getList() !!};
            $('#booth-table').DataTable({
                ajax: '{!! route('booth.data') !!}',
                columns: [
                    {data: 'id'},
                    {
                        data: 'type',
                        render: function (data, type, full, meta) {
                            if (data == null || types[data.id] === undefined) {
                                return '';
                            }
                            return types[data.id]['tag'];
                        }
                    },
                    {data: 'name'},
                    {
                        searchable: false,
                        sortable: false,
                        data: 'image',
                        render: function (data, type, full, meta) {
                            if (!data) {
                                return '';
                            }
                            return '<img src="' + data + '" class="ui small centered rounded image"/>';
                        }
                    },
                    {
                        searchable: false,
                        sortable: false,
                        data: 'id',
                        render: function (data, type, full, meta) {
                            var btnBar = '';
                            btnBar += '<a href="{{ route('booth.index') }}/' + data + '" class="ui icon blue inverted button" data-tooltip="攤位資料" data-position="right center" data-inverted=""><i class="search icon"></i></a>';
                            btnBar += '<a href="{{ route('booth.index') }}/' + data + '/edit" class="ui icon brown inverted button" data-tooltip="編輯攤位" data-position="right center" data-inverted=""><i class="edit icon"></i></a>';
                            btnBar += '<form method="POST" action="{{ route('booth.index') }}/' + data + '" accept-charset="UTF-8" style="display: inline" onsubmit="return confirm(\'確定要刪除此攤位嗎？\');">';
                            btnBar += '{{ method_field('DELETE') }}';
                            btnBar += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
                            btnBar += '<button type="submit" class="ui icon red inverted button" data-tooltip="刪除攤位" data-position="right center" data-inverted="">';
                            btnBar += '<i class="trash icon"></i>';
                            btnBar += '</button>';
                            btnBar += '</form>';
                            return btnBar;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
