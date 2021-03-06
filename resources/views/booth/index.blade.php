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
        <a href="{{ route('booth.downloadQRCode') }}" class="ui blue inverted labeled icon button">
            <i class="file word outline icon"></i>
            下載QR碼
        </a>
        <br/>
    @endif
    {{ Form::open(['id' => 'search_form', 'method' => 'get', 'style' => 'display: inline']) }}
    {!! SemanticForm::select('type_id')->options([''=>'-- 依類別檢視 --']+\App\Type::pluck('name', 'id')->toArray())->select(Request::get('type_id')) !!}
    @if(Request::get('type_id'))
        <a href="{{ route('booth.index') }}" class="ui icon button" title="檢視全部"><i class="remove icon"></i></a>
    @endif
    {{ Form::close() }}
    <div class="ui divider"></div>
    <table class="ui selectable celled padded unstackable table" id="booth-table">
        <thead>
        <tr>
            <th>攤位編號</th>
            <th>類型</th>
            <th>名稱</th>
            <th>圖片</th>
            @if(Entrust::can('booth.manage'))
                <th>操作</th>
            @endif
        </tr>
        </thead>
    </table>
@endsection

@section('js')
    <script>
        $(function () {
            $('#booth-table').DataTable({
                ajax: '{!! route('booth.data', ['type_id' => Request::get('type_id')]) !!}',
                columns: [
                    {data: 'number'},
                    {
                        data: 'type_id',
                        render: function (data, type, full, meta) {
                            if (!full.type) {
                                return '';
                            }
                            return full.type.tag;
                        }
                    },
                    {
                        data: 'name',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                return '<a href="{{ route('booth.index') }}/' + full.id + '"><nobr>' + data + '</nobr></a>';
                            }
                            return data;
                        }
                    },
                    {
                        searchable: false,
                        sortable: false,
                        data: 'image',
                        render: function (data, type, full, meta) {
                            if (!data) {
                                return '';
                            }
                            return '<img src="' + full.thumbnail + '" class="ui small centered rounded image"/>';
                        }
                    }
                    @if(Entrust::can('booth.manage'))
                    , {
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
                    @endif
                ]
            });
            $('select[name=type_id]').change(function () {
                $('#search_form').submit();
            });
        });
    </script>
@endsection
