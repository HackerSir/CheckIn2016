@extends('app')

@section('title', '打卡集點記錄')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡集點記錄
    </h2>
    <a href="{{ route('point.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增打卡集點記錄
    </a>
    <a href="{{ route('point.downloadXlsxFile') }}" class="ui blue inverted labeled icon button">
        <i class="file excel outline icon"></i>
        下載Excel檔
    </a>
    <div class="ui divider"></div>
    <table class="ui selectable celled padded unstackable table" id="point-table">
        <thead>
        <tr>
            <th>#</th>
            <th>打卡集點者</th>
            <th>攤位</th>
            <th>打卡集點時間</th>
            <th>操作</th>
        </tr>
    </table>
@endsection

@section('js')
    <script>
        $(function () {
            $('#point-table').DataTable({
                ajax: '{!! route('point.data') !!}',
                order: [[0, 'desc']],
                columns: [
                    {data: 'id'},
                    {
                        data: 'student_nid',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                return '<a href="{{ route('student.index') }}/' + full.student.nid + '" target="_blank">' + full.student.displayName + '</a>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'booth_id',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                var html = '<a href="{{ route('booth.index') }}/' + full.booth.id + '" target="_blank">';
                                if (full.booth.type) {
                                    html += full.booth.type.tag + ' ';
                                }
                                html += full.booth.name + '</a>';
                                return html;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'check_at'
                    },
                    {
                        searchable: false,
                        sortable: false,
                        data: 'id',
                        render: function (data, type, full, meta) {
                            var btnBar = '';
                            btnBar += '<form method="POST" action="{{ route('point.index') }}/' + data + '" accept-charset="UTF-8" style="display: inline" onsubmit="return confirm(\'確定要刪除此打卡集點記錄嗎？\');">';
                            btnBar += '{{ method_field('DELETE') }}';
                            btnBar += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
                            btnBar += '<button type="submit" class="ui icon red inverted button" data-tooltip="刪除打卡集點記錄" data-position="right center" data-inverted="">';
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
