@extends('front.header', ['auth' => true])
@section('auth')
    <div class="content">
        <div class="row">
            <div class="col-md-7">
                <div class="jumbotron">

                    <div class="alert alert-info">
                        Untuk keselamatan account anda, diharapkan menutup browser anda setelah selesai mengakses halaman login.
                        <br>
                        Berhati-hatilah terhadap program atau website yang meminta id dan password akun anda.
                    </div>

                    <br>
                    <h3 class="form-title font-green">Donasi Support</h3>
                    <br>
                    <div class="row">
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (1).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (2).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (3).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (4).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (5).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3 col-xs-4">
                            <img src="{{url('img/dummy/img (6).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
			<div class="col-sm-3">
                            <img src="{{url('img/dummy/img (7).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
			<div class="col-sm-3">
                            <img src="{{url('img/dummy/img (8).jpg')}}" class="img-responsive">
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <!-- BEGIN LOGIN FORM -->
                <div class="jumbotron">

                        <form class="login-form" action="{{ url( 'auth/login' ) }}" method="post">
                            <h2 class="form-title font-green">{{ trans( 'main.signin.title' ) }}</h2>
                            <br>
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                <span> {{ trans( 'main.signin.error' ) }} </span>
                            </div>

                            @if (session('msg'))
                                <div class="alert alert-info">
                                    {{ session('msg') }}
                                </div>
                            @endif
                            @if ( $errors->any() )
                                <div class="alert alert-danger">
                                    @foreach( $errors->all() as $e )
                                        <span>{{ $e }}</span><br>
                                    @endforeach
                                </div>
                            @endif
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                                <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signin.username' ) }}</label>
                                <input class="form-control form-control-solid placeholder-no-fix" type="text" placeholder="{{ trans( 'main.signin.username' ) }}" name="username" required /> </div>
                            <div class="form-group">
                                <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signin.password' ) }}</label>
                                <input class="form-control form-control-solid placeholder-no-fix" type="password" placeholder="{{ trans( 'main.signin.password' ) }}" name="password" required /> 
                            </div>
                            <div class="form-actions text-center">
                                <button type="submit" class="btn green uppercase pull-right">{{ trans( 'main.signin.button' ) }}</button>
                                <!--<a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>-->
                            </div>
                            <div class="create-account">
                                <a href="{{url('auth/register')}}" class="uppercase pull-left" style="margin-top: 10px;">{{ trans( 'main.signin.create' ) }}</a>
                                <div class="clearfix"></div>
                            </div>
                        </form>
			<p><a class="uppercase pull-left" style="font-size: 15px;font-weight: 500;" href="{{url('auth/reset')}}">Reset Password</a></p> 
                    </div>
            </div>
        </div>
        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <!--<form class="forget-form" action="{{ route( 'password.email' ) }}" method="post">
			
            <h3 class="font-green">Forget Password ?</h3>
            <p> Enter your e-mail address below to reset your password. </p>
            {!! csrf_field() !!}
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn btn-default">Back</button>
                <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>-->
        <!-- END FORGOT PASSWORD FORM -->
        <!-- BEGIN REGISTRATION FORM -->
        <!-- END REGISTRATION FORM -->
    </div>
@stop