@extends('layouts.app')

@section('content')
<div class="flex-center-high">
    <div class="box" style="font-size: 18px; width: 300px;">
        <div class="title is-4">Reset Password</div>

        <div>
            <form role="form" method="POST" action="{{ url('/password/reset') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="label" for="email">E-Mail Address</label>

                    <div>
                        <input class="input" id="email" type="email" name="email" value="{{ $email or old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help is-danger">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="label" for="password">Password</label>

                    <div>
                        <input class="input" id="password" type="password" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help is-danger">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label class="label" for="password-confirm">Confirm Password</label>
                    <div>
                        <input class="input" id="password-confirm" type="password" name="password_confirmation" required>

                        @if ($errors->has('password_confirmation'))
                            <span class="help is-danger">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="button is-success">
                            Reset Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
