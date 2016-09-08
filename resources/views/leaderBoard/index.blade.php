@extends('app')

@section('title', '打卡次數排行榜')

@section('content')
    <h2 class="ui teal header center aligned">
        打卡次數排行榜
    </h2>
    <div class="ui warning message">
        <ul class="list">
            <li>僅計算新生人數</li>
            <li>同一人在同一攤位打卡，無論次數，僅計一次</li>
        </ul>
    </div>
    <table class="ui selectable celled padded unstackable table" id="booth-table">
        <thead>
        <tr>
            <th>名次</th>
            <th>攤位編號</th>
            <th>攤位</th>
            <th>打卡新生數</th>
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
                <td>{{ $boothPointCount[$booth->id] or 0 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
