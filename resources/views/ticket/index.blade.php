@extends('app')

@section('title', '抽獎券')

@section('content')
    <h2 class="ui teal header center aligned">
        抽獎券
    </h2>
    <table class="ui selectable celled padded unstackable table" id="ticket-table">
        <thead>
        <tr>
            <th>抽獎編號</th>
            <th>學生</th>
            <th>時間</th>
            <th>操作</th>
        </tr>
    </table>
@endsection

@section('js')
    <script>
        $(function () {
            var $dataTable = $('#ticket-table');
            $dataTable.DataTable({
                ajax: '{!! route('ticket.data') !!}',
                columns: [
                    {data: 'id'},
                    {
                        data: 'student_nid',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                return full.student.displayName;
                            }
                            return data;
                        }
                    },
                    {
                        searchable: false,
                        data: 'created_at'
                    },
                    {
                        searchable: false,
                        sortable: false,
                        data: 'id',
                        render: function (data, type, full, meta) {
                            var btnBar = '';
                            btnBar += '<form method="POST" action="{{ route('ticket.index') }}/' + data + '" accept-charset="UTF-8" style="display: inline" onsubmit="return confirm(\'確定要刪除此抽獎券嗎？\');">';
                            btnBar += '{{ method_field('DELETE') }}';
                            btnBar += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
                            btnBar += '<button type="submit" class="ui icon red inverted button" data-tooltip="刪除抽獎券" data-position="right center" data-inverted="">';
                            btnBar += '<i class="trash icon"></i>';
                            btnBar += '</button>';
                            btnBar += '</form>';
                            return btnBar;
                        }
                    }
                ]
            });
            $dataTable.parent().addClass('table-responsive');
        });
    </script>
@endsection
