@extends('app')

@section('title', '註冊')

@section('content')
    <div class="ui top attached segment">
        <div class="ui top attached label">註冊</div>
        <div class="ui error message">
            <div class="header">
                注意
            </div>
            <ul class="list">
                <li>若您是逢甲大學學生，請<b>不要</b>註冊帳號，請直接使用{{ link_to_route('oauth.index', 'NID快速登入') }}</li>
                <li>若您不是逢甲大學學生，請<b>不要</b>註冊帳號，註冊並不會令您取得任何權限</li>
                <li>若您堅持註冊帳號，請確定您明確了解註冊流程，並明白透過收取信件驗證帳號之必要性</li>
            </ul>
        </div>
        <div class="ui large aligned center aligned relaxed stackable grid">
            <div class="six wide column">
                <h2 class="ui teal image header">
                    Register
                </h2>
                {!! SemanticForm::open()->post()->action(action('Auth\AuthController@register'))->addClass('large') !!}
                <div class="ui stacked segment">
                    <div class="field{{ $errors->has('name') ? ' error' : '' }}">
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            {!! SemanticForm::text('name')->placeholder('Name / Nickname')->required() !!}
                        </div>
                    </div>
                    <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                        <div class="ui left icon input">
                            <i class="mail icon"></i>
                            {!! SemanticForm::email('email')->placeholder('E-mail address')->required() !!}
                        </div>
                    </div>
                    <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            {!! SemanticForm::password('password')->placeholder('Password')->required() !!}
                        </div>
                    </div>
                    <div class="field{{ $errors->has('password_confirmation') ? ' error' : '' }}">
                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            {!! SemanticForm::password('password_confirmation')->placeholder('Password Confirmation')->required() !!}
                        </div>
                    </div>
                    {!! SemanticForm::submit('Register')->addClass('fluid large teal submit') !!}
                </div>

                @if($errors->count())
                    <div class="ui error message" style="display: block">
                        <ul class="list">
                            @foreach($errors->all('<li>:message</li>') as $error)
                                {!! $error !!}
                            @endforeach
                        </ul>
                    </div>
                @endif
                {!! SemanticForm::close() !!}
            </div>
            <div class="ui vertical divider" style="height: 25% !important;">OR</div>
            <div class="six wide column">
                <h2>Already have an account?</h2>
                <p>You can just {{ link_to_action('Auth\AuthController@showLoginForm', 'Sign in') }}!</p>

                @if(\App\Setting::getRaw('AllowedEmailDomains'))
                    <div class="ui horizontal divider">&nbsp;</div>
                    <h2>Allowed email domains</h2>
                    <ul style="text-align: left">
                        @foreach(preg_split('/$\R?^/m', \App\Setting::getRaw('AllowedEmailDomains')) as $domain)
                            <li>{{ $domain }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
