<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ pagetitle()->get() }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset( 'css/front/main.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'css/front/plugins.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'css/front/global.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'css/front/layout.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'css/front/'.settings('tema_depan') ) }}.css">

    <link href="{{ asset( 'css/front/login.min.css' ) }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
	@yield('add-css')
</head>
    <body class="page-container-bg-solid page-boxed">

        @include('front.navbar')

        <div class="page-container">
            <div class="page-content-wrapper">
				{!! Breadcrumbs::render() !!}
                <div class="page-content">
                    <div class="container">
                        <div class="page-content-inner">
                            @if(@$auth == true)
                                @yield('auth')
                            @else
                                @include( 'errors.list' )
                                @include( 'flash::message' )
                                <div class="row">
                                    <h4> {{Request::is()}}</h4>
                                    @unless( Request::is( 'shop*' ) )
                                        <div class="col-xs-12">
                                            @include( 'front.widgets' )
                                        </div>
                                    @endunless
                                    <div class="col-xs-12">
                                        @yield( 'content' )
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include( 'front.footer' )
        @yield('modal')

        <script src="{{ asset( 'js/front/main.js' ) }}"></script>
        <script src="{{ asset( 'js/front/plugins.js' ) }}"></script>
        <script src="{{ asset( 'js/front/global.js' ) }}"></script>
        <script src="{{ asset( 'js/front/layout.js' ) }}"></script>
        <script src="{{ asset( 'js/front/login.min.js' ) }}"></script>
        <script src="{{ asset( 'js/front/custom.js' ) }}"></script>
        @yield('js')

        @yield( 'footer' )
    </body>
</html>