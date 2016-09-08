@extends('app')

@section('title', '排行榜')

@section('content')
    <h2 class="ui teal header center aligned">
        攤位
    </h2>
    <table class="ui selectable celled padded unstackable table" id="booth-table">
        <thead>
        <tr>
            <th>名次</th>
            <th>攤位編號</th>
            <th>攤位</th>
            <th>打卡次數</th>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @foreach($booths as $index => $booth)
            <tr>
                {{-- FIXME: 同名次顯示 --}}
                <td>{{ $i++ }}</td>
                <td>{{ $booth->number }}</td>
                <td>{!! $booth->type->tag or '' !!} {{ link_to_route('booth.show', $booth->name, $booth) }}</td>
                <td>{{ $booth->points->count() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
