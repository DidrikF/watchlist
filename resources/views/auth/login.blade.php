@extends('layouts.app')

@section('content')
<div class="flex-center-high">
    <div class="std-form">
        <div class="heading">Login</div>
        <div>
            <form method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">E-Mail Address</label>

                    <div>
                        <input id="email" class="max-width" type="email" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span>
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password</label>

                    <div>
                        <input id="password" class="max-width" type="password" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                </div>
                <div>
                    <button type="submit">
                        Login
                    </button>

                    <a href="{{ url('/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>                
            </form>
        </div>
    </div>
</div>
@endsection
