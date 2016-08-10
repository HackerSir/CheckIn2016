<div class="field search required{{ ($errors->has('color'))?' error':'' }}">
    <label>使用者</label>
    <div class="ui fluid search selection dropdown">
        <input type="hidden" name="user_id">
        <i class="dropdown icon"></i>
        <div class="default text">選擇使用者</div>
        <div class="menu">
            @foreach(\App\User::all() as $user)
                <div class="item" data-value="{{ $user->id }}">{{ $user->name }}</div>
            @endforeach
        </div>
    </div>
</div>
