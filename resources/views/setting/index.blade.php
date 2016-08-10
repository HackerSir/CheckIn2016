@extends('app')

@section('title', '網站設定')

@section('css')
    {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/css/jqueryui-editable.css') !!}
    <style>
        {{-- Make inline editables take the full width of their parents
         http://patabugen.co.uk/2014/05/29/full-width-inline-x-editable-elements/ --}}
        .editable-container.editable-inline,
        .editable-container.editable-inline .control-group.form-group,
        .editable-container.editable-inline .control-group.form-group .editable-input,
        .editable-container.editable-inline .control-group.form-group .editable-input textarea,
        .editable-container.editable-inline .control-group.form-group .editable-input select,
        .editable-container.editable-inline .control-group.form-group .editable-input input:not([type=radio]):not([type=checkbox]):not([type=submit]) {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <h2 class="ui teal header center aligned">
        網站設定
    </h2>
    <table class="ui selectable celled padded unstackable table">
        <thead>
        <tr>
            <th class="eight wide">設定項目</th>
            <th class="eight wide">資料</th>
        </tr>
        </thead>
        <tbody>
        @foreach($settings as $setting)
            <td>
                {{ $setting->name }}（{{ $setting->getTypeDesc() }}）<br/>
                <small><i class="angle double right icon"></i> {{ $setting->desc }}</small>
            </td>
            <td>
                @if($setting->getType() == 'boolean')
                    <div class="editableField" data-pk="{{ $setting->id }}" data-type="select" data-url="{{ route('setting.update', $setting->id) }}" data-source="[{'value':1, 'text':'✔ True'},{'value':0, 'text':'✘ False'}]" data-value="{{ ($setting->getData()) ? 1 : 0 }}">{{ ($setting->getData()) ? '✔ True' : '✘ False' }}</div>
                @else
                    <div class="editableField" data-pk="{{ $setting->id }}" data-type="{{ $setting->getHtmlFieldType() }}" data-url="{{ route('setting.update', $setting->id) }}">{{ $setting->data }}</div>
                @endif
            </td>
        @endforeach
        </tbody>
    </table>
@endsection

@section('js')
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js') !!}
    <script>
        $.fn.editable.defaults.mode = 'inline';
        $(function () {
            $('.editableField').editable({
                ajaxOptions: {
                    headers: {
                        "X-CSRF-Token": "{{ Session::token() }}",
                        "Accept": "application/json"
                    },
                    type: 'put',
                    dataType: 'json',
                    success: function (response, newValue) {
                        if (!response.success) return response.msg;
                    },
                    error: function (response, newValue) {
                        if (response.status === 500) {
                            return 'Service unavailable. Please try later.';
                        } else {
                            return response.responseText;
                        }
                    }
                },
                emptytext: '尚未設定',
                rows: '10',
                showbuttons: 'bottom'
            });
        });
    </script>
@endsection
