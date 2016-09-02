<div class="field search required{{ ($errors->has('student_nid'))?' error':'' }}">
    <label>學生</label>
    <div class="ui fluid search selection dropdown">
        <input type="hidden" name="student_nid" value="{{ Request::old('student_nid') }}">
        <i class="dropdown icon"></i>
        <div class="default text">選擇學生</div>
        <div class="menu">
            @foreach(\App\Student::all() as $student)
                <div class="item" data-value="{{ $student->nid }}">{{ $student->displayName }}</div>
            @endforeach
        </div>
    </div>
</div>
