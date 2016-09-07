@extends('app')

@section('title', '抽獎券')

@section('content')
    <h2 class="ui teal header center aligned">
        抽獎券
    </h2>
    <a href="{{ route('ticket.ticket') }}" class="ui blue inverted icon button" target="_blank">
        <i class="external icon" aria-hidden="true"></i> 抽獎券展示
    </a>
    <table class="ui selectable celled padded unstackable table" id="ticket-table">
        <thead>
        <tr>
            <th>抽獎編號</th>
            <th>學生</th>
            <th>時間</th>
            <th>操作</th>
        </tr>
        </thead>
    </table>
@endsection

@section('js')
    <script>
        $(function () {
            $('#ticket-table').DataTable({
                ajax: '{!! route('ticket.data') !!}',
                order: [[0, 'desc']],
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
        });
    </script>
@endsection
