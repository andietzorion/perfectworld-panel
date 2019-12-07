@extends('front.header', ['auth' => true])

@section('auth')
    <div class="jumbotron" style="min-width: 300px;max-width: 500px;margin: 20px auto 40px;">
        <form class="login-form" method="POST" action="{{ url('auth/resetpw') }}">
            <h2 class="form-title font-green">Buat Password Baru</h2>
            <br>

            @if ( $errors->any() )
                <div class="alert alert-danger">
                    @foreach( $errors->all() as $e )
                        <span>{{ $e }}</span><br>
                    @endforeach
                </div>
            @endif

            @if (session('msg'))
                <div class="alert alert-info">
                    {{ session('msg') }}
                </div>
            @endif

            {!! csrf_field() !!}
            <input type="hidden" name="email" value="{{$email}}">
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label">Password</label>
                <input type="password" class="form-control form-control-solid placeholder-no-fix" name="password">
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label">Ketik Ulang Password</label>
                <input type="password" class="form-control form-control-solid placeholder-no-fix" name="password_confirmation">
            </div>
            <div class="form-actions text-right">
                <button type="submit" class="btn green uppercase">Reset</button>
            </div>
        </form>
    </div>
@stop