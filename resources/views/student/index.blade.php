@extends('app')

@section('title', '學生清單')

@section('content')
    <h2 class="ui teal header center aligned">
        學生清單
    </h2>
    <a href="{{ route('student.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增學生
    </a>
    <div class="ui divider"></div>
    <table class="ui selectable celled padded unstackable table" id="student-table">
        <thead>
        <tr>
            <th>學生</th>
            <th>系級</th>
            <th>入學年度</th>
            <th>性別</th>
            <th>操作</th>
        </tr>
        </thead>
    </table>
@endsection


@section('js')
    <script>
        $(function () {
            $('#student-table').DataTable({
                ajax: '{!! route('student.data') !!}',
                columns: [
                    {
                        data: 'nid',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                return full.displayName;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'class',
                        render: function (data, type, full, meta) {
                            if (type === 'display') {
                                return data + '<br/>（' + full.unit_name + ' / ' + full.dept_name + '）';
                            }
                            return data;
                        }
                    },
                    {data: 'in_year'},
                    {data: 'sex'},
                    {
                        searchable: false,
                        sortable: false,
                        data: 'nid',
                        render: function (data, type, full, meta) {
                            var btnBar = '';
                            btnBar += '<form method="POST" action="{{ route('student.index') }}/' + data + '/fetch" style="display: inline">';
                            btnBar += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
                            btnBar += '<button type="submit" class="ui icon blue inverted button" data-tooltip="更新資料" data-position="right center" data-inverted="">';
                            btnBar += '<i class="refresh icon"></i>';
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
