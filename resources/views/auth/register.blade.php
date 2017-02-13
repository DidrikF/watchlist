@extends('layouts.app')

@section('content')
<div style="width: 350px; margin: 0 auto; margin-top: 50px;">
    <div class="box" style="font-size: 18px;">
        <div class="title is-4">Register</div>
        <div>
            <form role="form" method="POST" action="{{ url('/register') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="label" for="name">Name</label>

                    <div>
                        <input class="input" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help is-danger">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="label" for="email">E-Mail Address</label>

                    <div>
                        <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required>

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

                <div>
                    <label class="label" for="password-confirm">Confirm Password</label>

                    <div>
                        <input class="input" id="password-confirm" type="password" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="button is-success">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
