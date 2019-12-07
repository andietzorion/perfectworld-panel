@extends('front.header', ['auth' => true])

@section('auth')
    <div class="jumbotron" style="min-width: 300px;max-width: 500px;margin: 20px auto 40px;">
        <form class="login-form" method="POST" action="{{ url('auth/reset') }}">
            <h2 class="form-title font-green">Masukan Email</h2>
            <br>

            @if (session('msg'))
                <div class="alert alert-info">
                    {{ session('msg') }}
                </div>
            @endif

            {!! csrf_field() !!}
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label">Email</label>
                <input id="email" type="email" class="form-control form-control-solid placeholder-no-fix" name="email">
            </div>
            <div class="form-actions text-right">
                <button type="submit" class="btn green uppercase">Kirim Link</button>
            </div>
        </form>
    </div>
@stop