@extends('app')

@section('title', '網站設定')

@section('css')
    {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap-editable/css/bootstrap-editable.css') !!}
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
    <div class="container">
        <div class="page-header">
            <h1>網站設定</h1>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center col-md-4">設定項目</th>
                <th class="text-center col-md-8">資料</th>
            </tr>
            </thead>
            <tbody>
            @foreach($settings as $setting)
                <tr>
                    <td class="col-md-4">
                        {{ $setting->name }}<span class="text-muted">（{{ $setting->getTypeDesc() }}）</span><br/>
                        <small><i class="fa fa-angle-double-right"></i> {{ $setting->desc }}</small>
                    </td>
                    <td class="col-md-8">
                        @if($setting->getType() == 'boolean')
                            <div class="editableField" data-pk="{{ $setting->id }}" data-type="select" data-url="{{ route('setting.update', $setting->id) }}" data-source="[{'value':1, 'text':'✔ True'},{'value':0, 'text':'✘ False'}]" data-value="{{ ($setting->getData()) ? 1 : 0 }}">{{ ($setting->getData()) ? '✔ True' : '✘ False' }}</div>
                        @else
                            <div class="editableField" data-pk="{{ $setting->id }}" data-type="{{ $setting->getHtmlFieldType() }}" data-url="{{ route('setting.update', $setting->id) }}">{{ $setting->data }}</div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.js') !!}
    <script>
        $.fn.editable.defaults.mode = 'inline';
        $(document).ready(function () {
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
