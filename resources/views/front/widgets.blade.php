<?php 
    $klas = 'col-lg-4';
    if(count( $gms ) > 0){
        $klas = 'col-lg-3';
    }
?>

<div class="row">
    @if ( count( $gms ) > 0 )
        <div class="col-lg-3">

            <div class="dashboard-stat yellow">
                <a href="#!" data-toggle="modal" data-target="#gm" style="display: block;">
                    <div class="visual">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span>{{count($gms)}}</span>
                        </div>
                        <div class="desc">{{ trans( 'widget.gms_title' ) }}</div>
                    </div>
                </a>
            </div>
        
        </div>
    @endif

    <div class="{{$klas}}">
        <div class="dashboard-stat {{ $api->online ? 'green-jungle' : 'red' }}">
            <div class="visual">
                <i class="{{ $api->online ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span>{{ $api->online ? trans( 'widget.server_status_online' ) : trans( 'widget.server_status_offline' ) }}</span>
                </div>
                <div class="desc"> {{ trans( 'widget.server_status_title' ) }} </div>
            </div>
        </div>
    </div>
    <div class="{{$klas}}">
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="icon-user"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ DB::table('point')->where('zoneid', 1)->count() }}">0</span>
                </div>
                <div class="desc"> {{ trans( 'widget.players_online_title' ) }} </div>
            </div>
        </div>
    </div>
    <div class="{{$klas}}">
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="icon-users"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ DB::table('users')->count() }}">0</span>
                </div>
                <div class="desc"> {{ trans( 'widget.acc_registered_title' ) }} </div>
            </div>
        </div>
    </div>
</div>


@section('modal')

    @if(count($gms) > 0)
        <div class="modal fade" id="gm" tabindex="-1" role="dialog" aria-labelledby="gmLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">{{ trans( 'widget.gms_title' ) }}</h3>
              </div>
              <div class="modal-body">
                    @foreach( $gms as $gm )
                        <div>
                             {{ $gm->truename }}
                            <span class="pull-right badge badge-{{ $gm->online() == TRUE ? 'success' : 'danger' }} badge-roundless"> {{ $gm->online() ? trans( 'widget.server_status_online' ) : trans( 'widget.server_status_offline' ) }} </span>
                            <hr>
                        </div>
                    @endforeach
              </div>
            </div>
          </div>
        </div>
    @endif

@stop
