@extends('layouts.app')

@section('content')
<div style="width: 350px; margin: 0 auto; margin-top: 50px;">
    <div class="box" style="font-size: 18px">
        <div class="title is-4">Login</div>
        <div>
            <form method="POST" action="{{ url('/livedemo/companywatchlist/login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="label" for="email">E-Mail Address</label>

                    <div>
                        <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email') && ! $errors->has('accepted'))
                            <span class="help is-danger">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                        @if ($errors->has('accepted'))
                            <span class="help is-danger">
                                {{ $errors->first('accepted') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="label" for="password">Password</label>

                    <div>
                        <input id="password" class="input" type="password" name="password" required>

                        @if ($errors->has('password') && ! $errors->has('accepted'))
                            <span class="help is-danger">
                               {{ $errors->first('password') }}
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
                    <button class="button is-success" type="submit">
                        Login
                    </button>

                    <a href="{{ url('/livedemo/companywatchlist/password/reset') }}">
                        Forgot Your Password?
                    </a>
                </div>                
            </form>
        </div>
    </div>
</div>
@endsection
