@extends('app')

@section('title', '登入')

@section('css')
    <style>
        .linkText {
            font-size: 2em;
            white-space: nowrap;
        }

        #OAuthLink:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 3em">
        <a href="{{ route('oauth.index') }}" id="OAuthLink">
            <img src="{{ asset('img/nid.gif') }}" alt="NID登入" class="ui centered medium image"><br/>
            <span class="linkText"><i class="pointing right icon"></i>使用 NID 快速登入<i
                    class="pointing left icon"></i></span></a><br/>
        <span style="color: grey">（目前僅校內網路可使用）</span>
    </div>
    <div style="text-align: center;">
        <a href="javascript:void(0)" style="color: grey;" id="showBtn">[傳統登入]</a>
    </div>
    <div class="ui top attached segment" hidden id="loginSegment">
        <div class="ui top attached label">傳統登入</div>
        <div class="ui error message">
            <div class="header">
                注意
            </div>
            <ul class="list">
                <li>若您是逢甲大學學生，請<b>不要</b>使用此功能，請直接使用{{ link_to_route('oauth.index', 'NID快速登入') }}</li>
            </ul>
        </div>
        <div class="ui large aligned center aligned relaxed stackable grid">
            <div class="six wide column">
                <h2 class="ui teal image header">
                    Login
                </h2>
                {!! SemanticForm::open()->action(action('Auth\AuthController@login'))->addClass('large') !!}
                <div class="ui stacked segment">
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
                    {!! SemanticForm::checkbox('remember')->label('Remember Me') !!}
                    {!! SemanticForm::submit('Login')->addClass('fluid large teal submit') !!}
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
                <h2>No Account?</h2>
                <p>You can just {{ link_to_action('Auth\AuthController@showRegistrationForm', 'Sign up') }}!</p>
                <div class="ui horizontal divider">
                    Or
                </div>
                <h2>Forgot Password?</h2>
                <p>Well, now you
                    can {{ link_to_action('Auth\PasswordController@showResetForm', 'Reset your password') }}.</p>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#showBtn').click(function () {
                $('#loginSegment').toggle();
            });
        });
    </script>
@endsection
