<div class="navbar-black">

    <div class="container-fluid">
        <div class="navbar-black-content">
            <div class="navbar-logo">
                <img src="{{ asset( 'img/logo.png' ) }}" alt="Perfect World">
                <a class="logo-default navbar-brand font-dark" href="{{ url( '/' ) }}">
                    <b class="text-capitalize">{{ settings( 'server_name', 'Perfect World Panel' ) }}</b>
                        <br>
                    <small class="text-capitalize">{{ settings( 'server_desc', 'Private Server Indonesia' ) }}</small>
                </a>
                <a href="#" class="menu-toggler">
                    <span class="fa fa-bars"></span>
                </a>
            </div>
            <ul class="nav navbar-nav navbar-account pull-right">
                @if ( Auth::guest() )
                    <li>
                        <a href="{{ url( 'auth/login' ) }}" class="">
                            {{ trans( 'main.login' ) }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url( 'auth/register' ) }}" class="">
                            {{ trans( 'main.register' ) }}
                        </a>
                    </li>
                @else
                    <li class="dropdown dropdown-dark">
                        <a href="#" class="dropdown-toggle {{ Auth::user()->characterId() ? '' :'font-white' }}" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            {{ Auth::user()->characterId() ? Auth::user()->characterName() : trans( 'main.select_character' ) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            @if ( $api->online )
                                {{--*/ $roles = Auth::user()->roles() /*--}}
                                @if ( count( $roles ) > 0 )
                                    @foreach( $roles as $role )
                                        <li>
                                            <a href="{{ url( 'character/select/' . $role['id'] ) }}">
                                                {{ $role['name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <a href="#">
                                            {{ trans( 'main.char_list_error' ) }}
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a href="#">
                                        {{ trans( 'main.server_not_online' ) }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="dropdown dropdown-dark">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            @if ( !$agent->isMobile() )
                                <img class="avatar" src="{{ Avatar::create( strtoupper( Auth::user()->name ) )->toBase64() }}" />
                            @endif
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            @if ( Auth::user()->isAdmin() )
                                <li>
                                    <a href="{{ url( 'admin' ) }}" target="_blank">
                                        <i class="icon-rocket"></i> {{ trans( 'main.acp_link' ) }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ url( 'donate' ) }}">
                                    <i class="fa fa-dollar"></i> 
                                    {{ trans( 'main.acc_balance', ['money' => Auth::user()->balance(), 'currency' => settings('currency_name')] ) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url( 'account/settings' ) }}">
                                    <i class="icon-user"></i> {{ trans( 'main.acc_settings' ) }}
                                </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="{{ url( 'auth/logout' ) }}">
                                    <i class="icon-logout"></i> {{ trans( 'main.logout' ) }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                    <li class="dropdown dropdown-dark">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-globe"></i>
                            <span class="arrow"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            @foreach( $languages as $language )
                                <li>
                                    <a href="{{ Request::url() . '?language=' . $language }}">
                                        <img src="{{ asset( 'img/flags/' . $language . '.png' ) }}"> {{ trans( 'language.' . $language ) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
            </ul>
        </div><!-- /.navbar-black-content -->
        <div class="navbar-black-content">
            <ul class="nav navbar-nav navbar-menu">
                @foreach($apps as $key => $app)
                    @if ($app->enabled)
                        <li {{ ( $app->key == 'news' ) ? ( Request::is( '/' )
                        ? 'class=active' : NULL ) : ( Request::is( $app->key .
                        '*' ) ? 'class=active' : NULL ) }}>
                            <a href="{{ ( $app->key == 'news' ) ? url( '/') : url( '/' . $app->key ) }}"> {{ trans( 'main.apps.' . $app->key ) }} </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div><!-- /.nabar-black -->

</div>