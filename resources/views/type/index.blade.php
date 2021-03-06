@extends('app')

@section('title', '攤位類型')

@section('content')
    <h2 class="ui teal header center aligned">
        攤位類型
    </h2>
    <a href="{{ route('type.create') }}" class="ui icon brown inverted button">
        <i class="plus icon" aria-hidden="true"></i> 新增攤位類型
    </a>
    <div class="table-responsive">
        <table class="ui selectable celled padded unstackable table">
            <thead>
            <tr>
                <th>名稱</th>
                <th>標籤</th>
                <th>攤位數量</th>
                <th>目標攤位數量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <nobr>（全部）</nobr>
                </td>
                <td></td>
                <td>{{ $boothCount }}</td>
                <td>
                    {{ $globalTarget = \App\Setting::get('GlobalTarget') }}
                    @if($boothCount < $globalTarget)
                        <i class="large red warning sign icon" title="目標高於攤位數量，請調整目標或增加攤位"></i>
                    @endif
                </td>
                <td>
                    <a href="{{ route('setting.index') }}" class="ui icon blue inverted button" title="目標請至「網站設定」頁面設定">
                        <i class="external icon"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <nobr>（不採計）</nobr>
                </td>
                <td></td>
                <td>{{ \App\Booth::count() - $boothCount }}</td>
                <td></td>
                <td></td>
            </tr>
            @foreach($types as $type)
                <tr>
                    <td>
                        <nobr>
                            @if($type->counted)
                                <i class="green checkmark icon" title="「全部」包含此類型"></i>
                            @else
                                <i class="red remove icon" title="「全部」不包含此類型"></i>
                            @endif
                            {{ $type->name }}
                        </nobr>
                    </td>
                    <td>
                        {!! $type->tag !!}
                    </td>
                    <td>
                        {{ count($type->booths) }}
                    </td>
                    <td>
                        {{ $type->target }}
                        @if(count($type->booths) < $type->target)
                            <i class="large red warning sign icon" title="目標高於攤位數量，請調整目標或增加攤位"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('type.edit', $type) }}" class="ui icon brown inverted button" title="編輯類型">
                            <i class="edit icon"></i>
                        </a>
                        {!! Form::open(['route' => ['type.destroy', $type], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' => "return confirm('確定要刪除此類型嗎？');"]) !!}
                        <button type="submit" class="ui icon red inverted button" title="刪除類型">
                            <i class="trash icon"></i>
                        </button>
                        {!! Form::close() !!}
                        <a href="{{ route('booth.downloadQRCode', [null, 'type' => $type->id]) }}" class="ui icon blue inverted button" title="下載QR碼">
                            <i class="file word outline icon"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>
                    <nobr>
                        <i class="green checkmark icon" title="「全部」包含此類型"></i>
                        （未分類）
                    </nobr>
                </td>
                <td></td>
                <td>{{ \App\Booth::whereNull('type_id')->count() }}</td>
                <td><i class="large info circle icon" title="無法為未分類攤位設定目標"></i></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
