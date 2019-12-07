<!-- BEGIN INNER FOOTER -->
<div class="page-footer">
    <!-- Don't Remove This! -->
    <div class="container">
    	<div class="row">
    		<div class="col-md-8">
    			<div class="row" style="padding: 20px 0;">
	    			<div class="col-md-4">
		    			<img src="{{asset('img/dummy/logo.png')}}" style="height: 60px;">
					</div>
					<div class="col-md-8">
		    			Support by <a href="https://www.gamemaster.id/" target="_blank">gamemaster.id</a>
						<hr class="elegant-hr">
				        Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}" class="text-capitalize font-white">{{ settings('server_name') }}</a> | All Rights Reserved
				    </div>
			    </div>
    		</div>
    		<div class="col-md-2">
    			<div class="time-wrap">
	    			<h4 style="margin-top: 0;">Server Time</h4>
	    			<div>
	    				<h2>{{date('H:m')}}</h2>
	    				<h5>{{date_default_timezone_get()}}</h5>
	    			</div>
    			</div>
    		</div>
    		<div class="col-md-2">
    			<div class="time-wrap">
	    			<h4 style="margin-top: 0;">Local Time</h4>
	    			<div>
	    				<h2 id="clientTime"></h2>
	    				<h5 id="clientTimeZone"></h5>
	    			</div>
    			</div>
    		</div>
    	</div>
        
    </div>
    <!-- Don't Remove This! -->
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->

@section('js')
	<script type="text/javascript">
		var tz 	= Intl.DateTimeFormat().resolvedOptions().timeZone;
		var d 	= new Date();

	  	$('#clientTime').html(("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2));
	  	$('#clientTimeZone').html(tz);
	</script>
@stop