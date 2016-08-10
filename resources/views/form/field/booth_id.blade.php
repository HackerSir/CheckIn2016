<div class="field search required{{ ($errors->has('booth_id'))?' error':'' }}">
    <label>攤位</label>
    <div class="ui fluid search selection dropdown">
        <input type="hidden" name="booth_id" value="{{ Request::old('booth_id') }}">
        <i class="dropdown icon"></i>
        <div class="default text">選擇攤位</div>
        <div class="menu">
            @foreach(\App\Booth::with('type')->orderBy('type_id')->get() as $booth)
                <div class="item" data-value="{{ $booth->id }}">{!! $booth->type->tag or ''  !!}{{ $booth->name }}</div>
            @endforeach
        </div>
    </div>
</div>
