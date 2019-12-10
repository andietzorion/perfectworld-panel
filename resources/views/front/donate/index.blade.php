@extends( 'front.header' )

@section( 'content' )
        @if ( !settings( 'paypal_client_id' ) && !settings( 'paymentwall_link' ) )
            <div class="portlet light">
                <div class="portlet-body text-center">
                    {{ trans( 'donate.no_methods' ) }}
                </div>
            </div>
        @else
            @if ( settings( 'form_status' ) )
                <div class="portlet light">

                    @if (session('msg'))
                        <div class="alert alert-info">
                            {{ session('msg') }}
                        </div>
                    @endif

                    <div class="portlet-body">
                        <form action="{{ url( 'donate/manual' ) }}" method="post" enctype="multipart/form-data" onsubmit="return form_check();">
                            {!! csrf_field() !!}
                            <legend>Transfer Manual</legend>

                            <div class="row">
                                <div class="col">
                                    <div align="center" style="margin: 15px;">
                                        <h4 style="margin: 0">{{settings( 'debit_bank' )}}</h4>
                                        <h2 style="margin: 5px 0 10px;font-weight: bold;">{{settings( 'debit_nomor' )}}</h2>
                                        <h5 style="margin: 0">a/n {{settings( 'debit_nama' )}}</h5>
                                    </div>  
                                </div>
                            </div>

                            @if ( $errors->any() )
                                <div class="alert alert-danger">
                                    @foreach( $errors->all() as $e )
                                        <span>{{ $e }}</span><br>
                                    @endforeach
                                </div>
                            @endif

                            <div class="col-md-12 mb-md">
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{Auth::user()->truename}}">
                                </div>
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="{{Auth::user()->name}}">
                                </div>
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>Jenis Donasi</label>
                                    <input type="text" name="jenis" class="form-control">
                                </div>
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>{{ settings( 'currency_name' ) }}</label>
                                    <input id="form_tokens" name="tokens" type="number" class="form-control" min="0">
                                </div>
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>Jumlah</label>
                                    <input type="number" min="0" id="jumlah" name="jumlah" class="form-control" readonly="">
                                </div>
                                <div class="form-group col-md-6 form-md-line-input">
                                    <label>Bukti Transfer</label>
                                    <input type="file" name="bukti" accept="image/*" id="inputFile">
                                    <img id="image_upload_preview" src="" alt="Preview Image" style="max-width: 300px;max-height: 600px;object-fit: contain;display: none;margin: 10px auto;"/>
                                </div>
                            </div>
                            <button class="btn btn-block btn-lg blue" type="submit">{{ trans( 'main.buy' ) }}</button>
                        </form>
                    </div>
                </div>
            @endif
            @if ( settings( 'paypal_client_id' ) )
                <div class="portlet light">
                    <div class="portlet-body">
                        <form action="{{ url( 'donate/paypal' ) }}" onsubmit="return donation_check();" method="post">
                            {!! csrf_field() !!}
                            <legend>{{ trans( 'donate.paypal_title' ) }}</legend>
                            <div class="col-md-12 mb-md">
                                @if( settings( 'paypal_double' ) )
                                    <div class="alert alert-info">
                                        {!! trans( 'donate.double_notice' ) !!}
                                    </div>
                                @endif
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div class="input-group left-addon right-addon">
                                        <span class="input-group-addon">$</span>
                                        <input id="donation_dollars" name="dollars" type="number" class="form-control">
                                        <span class="input-group-addon">=</span>
                                        <input id="donation_tokens" name="tokens" type="number" class="form-control">
                                        <span class="input-group-addon">{{ settings( 'currency_name' ) }}</span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block btn-lg blue" type="submit">{{ trans( 'main.buy' ) }}</button>
                        </form>
                    </div>
                </div>
            @endif
            @if ( settings( 'paymentwall_link' ) && settings( 'paymentwall_key' ) )
                <div class="portlet light">
                    <div class="portlet-body">
                        <legend>{{ trans( 'donate.paymentwall_title' ) }}</legend>
                        @if( settings( 'paymentwall_double' ) )
                            <div class="alert alert-info">
                                {!! trans( 'donate.double_notice' ) !!}
                            </div>
                        @endif
                        <iframe src="{{ str_replace( [ '[USER_ID]', '[USER_E]', '[USER_D]' ], [ Auth::user()->ID, Auth::user()->email, Auth::user()->creatime->timestamp ], settings( 'paymentwall_link' ) ) }}" width="100%" height="800" frameborder="0"></iframe>
                    </div>
                </div>
            @endif
        @endif
@endsection

@section( 'add-css' )
<style type="text/css">
    .portlet.light{
        width: 800px;
        margin: 0 auto 15px;
    }
    @media screen and (max-width: 400px){
        .portlet.light{
            width: 100%;
            margin: auto;
        }
    }
</style>
@endsection

@section( 'footer' )
    @parent
    <script>
        function form_check() {
            let dollar_minimum = "{{ settings('form_min') }}";
            let dollars_paypal = $('#form_tokens').val();
            let dollars = $('#jumlah').val();
            if (dollars == null || dollars == "" || dollars_paypal == null || dollars_paypal == "") {
                toastr.error("{!! trans( 'donate.error.message', ['currency' => settings('currency_name')] ) !!}", "{{ trans( 'donate.error.title' ) }}");
                return false;
            } else if ( parseFloat( dollars ) < dollar_minimum || parseFloat( dollars_paypal ) < dollar_minimum ) {
                toastr.error("{{ trans( 'donate.error.minimum', ['min' => settings('form_min')] ) }}", "{{ trans( 'donate.error.title' ) }}");
                return false;
            } else {
                return true;
            }
        }

        function donation_check() {
            dollar_minimum = "{{ settings('paypal_min') }}";
            dollars_paypal = $('#donation_tokens').val();
            dollars = $('#donation_dollars').val();
            if (dollars == null || dollars == "" || dollars_paypal == null || dollars_paypal == "") {
                toastr.error("{!! trans( 'donate.error.message', ['currency' => settings('currency_name')] ) !!}", "{{ trans( 'donate.error.title' ) }}");
                return false;
            } else if ( parseFloat( dollars ) < dollar_minimum || parseFloat( dollars_paypal ) < dollar_minimum ) {
                toastr.error("{{ trans( 'donate.error.minimum', ['min' => settings('form_min')] ) }}", "{{ trans( 'donate.error.title' ) }}");
                return false;
            } else {
                return true;
            }
        }

        function format_number(field_id, decimal_places) {
            field = $("#" + field_id);
            new_val = Math.round(field.val() * Math.pow(10, decimal_places)) / Math
                            .pow(10, decimal_places);
            if (parseFloat(new_val) != parseFloat(field.val()) || (field.val().charAt(0) == "0" && field.val().charAt(field.val().length - 1) != ".")) {
                field.val(new_val);
            }
        }


        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image_upload_preview').attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#inputFile").change(function () {
            readURL(this);
        });

        $(function() {
            var per_USD = "{{ settings('paypal_per') }}";
            var double_donation = "{{ settings( 'paypal_double' ) }}";
            $("#donation_dollars").on('input', function () {
                format_number("donation_dollars", 2);
                $("#donation_tokens").val($("#donation_dollars").val() * ( double_donation ? per_USD * 2 : per_USD ) );
                format_number("donation_tokens", 0);
            });
            $("#donation_tokens").on('input', function () {
                format_number("donation_tokens", 0);
                $("#donation_dollars").val($("#donation_tokens").val() / ( double_donation ? per_USD * 2 : per_USD ) );
                format_number("donation_dollars", 2);
            });
        });
        $(function() {
            var per = "{{ settings('form_per') }}";
            $("#form_tokens").on('input', function () {
                format_number("form_tokens", 2);
                $("#jumlah").val($("#form_tokens").val() * per );
                format_number("jumlah", 0);
            });
        });
    </script>
@endsection
