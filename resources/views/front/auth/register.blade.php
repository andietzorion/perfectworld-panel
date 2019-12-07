@extends('front.header', ['auth' => true])
@section('auth')
    <div class="content">

        <div class="row">
            <div class="col-md-7">
                <div class="jumbotron">

                    <center>
                    <h2>Create your Account</h2>
                        <p>Akun dapat digunakan untuk pembelian voucher di website</p>
                    </center>

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
                <div class="jumbotron">
                    <form class="register-form" action="{{ url( 'auth/register' ) }}" method="post">
                    <!-- <form class="" action="{{ url( 'auth/register' ) }}" method="post" novalidate=""> -->
                        <h2 class="font-green">{{ trans( 'main.signup.title' ) }}</h2>
                        <p class="hint"> {{ trans( 'main.signup.info' ) }} </p>
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.username' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{ trans( 'main.signup.username' ) }}" name="username" required value="{{ old('username') }}" /> 
                        </div>
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.password' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="{{ trans( 'main.signup.password' ) }}" name="password" required /> 
                        </div>
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.confirm' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="{{ trans( 'main.signup.confirm' ) }}" name="password_confirmation" required /> 
                        </div>
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.email' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="{{ trans( 'main.signup.email' ) }}" name="email" required value="{{old('email')}}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.pin' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="{{ trans( 'main.signup.pin' ) }}" name="pin" required />
                        </div>
                        <div class="form-group">
                        <label class="control-label visible-ie8 visible-ie9">{{ trans( 'main.signup.truename' ) }}</label>
                            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{{ trans( 'main.signup.truename' ) }}" name="truename" required value="{{old('truename')}}" /> 
                        </div>
                        <div class="form-group margin-top-20 margin-bottom-20">
                            <div id="register_tnc_error"></div>
                        </div>
                        <div class="form-actions">
                            <center>
                                <strong class="">Sebelum mendaftar harap membaca <a target="_blank" href="https://pwstarlight.com/ketentuan-pw-starlight/">PERSETUJUAN PEMAIN</a> terlebih dahulu</strong>
                            </center>

                            <hr>

                            <a href="{{url('auth/login')}}" class="uppercase pull-left" style="margin-top: 10px;">{{ trans( 'main.login' ) }}</a>

                            <!-- <button type="button" id="register-back-btn" class="btn btn-default">{{ trans( 'main.signup.back' ) }}</button> -->
                            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">{{ trans( 'main.signup.submit' ) }}</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@stop

@section('modal')

    @if(session('msg'))
    <div class="modal fade" id="alertReg" tabindex="-1" role="dialog" aria-labelledby="alertRegLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="padding: 15px 20px;">
                @if(gettype(session('msg')) == "array")
                    @foreach(session('msg') as $r)
                        <div style="margin-bottom: 5px;"><i class="fa fa-warning"></i>&nbsp; {{$r}}<hr></div>
                    @endforeach
                @else
                {{session('msg')}}
                @endif
            </h4>
          </div>
        </div>
      </div>
    </div>
    @endif

@stop

@section('js')
    <script type="text/javascript">
        var $cek = $('#alertReg');
        if($cek.length > 0){
            $cek.modal('show')
        }
    </script>
@stop