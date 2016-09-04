<div class="field{{ ($errors->has('type_id'))?' error':'' }}">
    <label>攤位類型</label>
    <div class="ui fluid selection dropdown">
        <input type="hidden" name="type_id" value="{{ isset($type_id) ? $type_id : Request::old('type_id') }}">
        <i class="dropdown icon"></i>
        <div class="default text">
            <span class="ui tag label single line" style="margin-left: 10px">請選擇攤位類型</span>
        </div>
        <div class="menu">
            <div class="item" data-value="">
                <span class="ui tag label single line" style="margin-left: 10px">請選擇攤位類型</span>
            </div>
            @foreach(\App\Type::all() as $type)
                <div class="item" data-value="{{ $type->id }}">
                    <span class="ui tag label single line {{ $type->color }}" style="margin-left: 10px">
                        {{ $type->name }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
