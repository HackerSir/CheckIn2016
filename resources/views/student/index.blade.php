@extends('app')

@section('title', '學生清單')

@section('content')
    <h2 class="ui teal header center aligned">
        學生清單
    </h2>
    <a href="{{ route('student.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增學生
    </a>
    <div class="table-responsive">
        <table class="ui selectable celled padded unstackable table">
            <thead>
            <tr>
                <th>學生</th>
                <th>系級</th>
                <th>入學年度</th>
                <th>性別</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $student)
                <tr>
                    <td>
                        {{ $student->displayName }}
                    </td>
                    <td>
                        {{ $student->class }}<br/>
                        （{{ $student->unit_name }} / {{ $student->dept_name }}）
                    </td>
                    <td>{{ $student->in_year }}</td>
                    <td>{{ $student->sex }}</td>
                    <td>
                        {!! Form::open(['route' => ['student.fetch', $student], 'style' => 'display: inline']) !!}
                        <button type="submit" class="ui icon blue inverted button" title="更新資料">
                            <i class="refresh icon"></i>
                        </button>
                        {!! Form::close() !!}
                        {!! Form::open(['route' => ['student.destroy', $student], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此學生嗎？');"]) !!}
                        <button type="submit" class="ui icon red inverted button" title="刪除學生">
                            <i class="trash icon"></i>
                        </button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include('components.pagination-bar', ['models' => $students])
@endsection
