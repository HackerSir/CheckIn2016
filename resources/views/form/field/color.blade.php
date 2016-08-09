<div class="field required{{ ($errors->has('color'))?' error':'' }}">
    <label>標籤顏色</label>
    <div class="ui fluid selection dropdown">
        @if(isset($model))
            <input type="hidden" name="color" value="{{ $model->color }}">
        @else
            <input type="hidden" name="color">
        @endif
        <i class="dropdown icon"></i>
        <div class="default text">
            <span class="ui tag label single line" style="margin-left: 10px">請選擇標籤顏色</span>
        </div>
        <div class="menu">
            @foreach(\App\Traits\ColorTagTrait::$validColors as $color)
                <div class="item" data-value="{{ $color }}">
                    <span class="ui tag label single line {{ $color }}" style="margin-left: 10px">{{ $color }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
