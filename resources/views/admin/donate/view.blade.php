@extends( 'admin.header' )

@section( 'content' )
    <div class="portlet light">
        <div class="portlet-body">
            <div class="portlet-body">
                <a class="btn btn-warning" href="{{route('admin.donate.index')}}">Pending</a>
                <a class="btn btn-success" href="{{route('admin.donate.index')}}?status=berhasil">Berhasil</a>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>ID Transaksi</th>
                                <th>Jenis Donasi</th>
                                <th colspan="2">Jumlah</th>
                                <th>Bukti Transaksi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $datas as $data )
                                <tr>
                                    <td id="code">{{$data->user->name}}</td>
                                    <td>{{$data->transaction_id}}</td>
                                    <td>{{$data->jenis_donasi}}</td>
                                    <td>{{$data->amount}} IDR</td>
                                    <td>{{$data->amount / settings('form_per')}} {{ settings( 'currency_name' ) }}</td>
                                    <td><img src="{{'../uploads/'.$data->bukti}}" class="popbox"></td>
                                    <td>{{$data->status}}</td>
                                    <td>
                                        <div class="fs20">
                                            @if($data->status == 'pending')
                                            <a class="font-green tooltips mr-md" data-placement="top" data-original-title="{{ trans( 'main.edit' ) }}" href="{{ route( 'admin.donate.edit', $data->transaction_id ) }}"><i class="fa fa-check"></i></a>
                                            @endif
<form action="{{ route( 'admin.donate.destroy', $data->transaction_id ) }}" method="post" id="form-{{$data->transaction_id}}">
    <!-- <input class="btn btn-default" type="submit" value="Delete" /> -->
    <input type="hidden" name="_method" value="delete" />
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <a onclick="document.getElementById('form-{{$data->transaction_id}}').submit();" href="javascript:{}" class="font-red tooltips delete" data-placement="top" data-original-title="{{ trans( 'main.remove' ) }}"><i class="icon-trash"></i></a>
</form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        {!! $datas->render() !!}
    </div>
@endsection

@section( 'footer' )
    <style type="text/css">
        body.popbox{
          overflow: hidden;
        }
        body.popbox .popbox-modal{
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 99999;
          opacity: 0;
          pointer-events: none;
          background-color: black;
          display: flex;
          align-items: center;
          justify-content: center;
          overflow: auto;
          transition: .3s ease;
        }
        body.popbox.open .popbox-modal{
          opacity: 1;
          pointer-events: auto;
        }
        body.popbox.open .popbox-modal img{
          height: 80%;
          width: auto;
          background-color: #ccc;
          -o-object-fit: contain;
             object-fit: contain;
          cursor: zoom-out;
        }
        .popbox:not(body){
          display: inline-flex;
          width: 100px;
          height: 100px;
          cursor: zoom-in;
          padding: 5px;
          background-color: #ccc;
          -o-object-fit: contain;
             object-fit: contain;
          -o-object-position: center center;
             object-position: center center;
        }
    </style>
    <script type="text/javascript">
        $('.popbox').click(function(){
          var src = $(this).attr('src');
          $('body').append('<div class="popbox-modal"><img src="'+src+'"></div>').addClass('popbox');
          setTimeout(function(){
            $('.popbox').addClass('open');
          }, 300);
          
          $('.popbox-modal').click(function(){
            $(this).fadeOut(300, function(){
              $(this).remove();
              $('body').removeClass('popbox open');
            });
          })
        })
    </script>
@endsection

