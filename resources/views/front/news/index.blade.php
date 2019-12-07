@extends( 'front.header' )
@section( 'add-css' )
	<link href="https://fonts.googleapis.com/css?family=Great+Vibes&display=swap" rel="stylesheet">
@endsection
@section( 'content' )
<div class="row no-gutters">
	<div class="col-lg-2 d-sm-none">

		 <!-- Start Left Widget -->

		<div class="card">
			<div class="card-header">
				<a href="#!">Facebook Page</a>
			</div>
			<div class="card-body-right">
			<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FPerfect-World-Orion-2189458357750920%2F&tabs=timeline&width=235&height=400&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=122861275004907" width="235" height="400" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>			
			</div>
		</div>

		<div class="card">
			<div class="card">
			<div class="card-header">
				<a href="#!">Our Youtube</a>
			</div>

			<div class="card-body">
			<iframe width="230" height="155" src="https://www.youtube.com/embed/QppX6V6BHlQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
	</div>
				
		<!-- End Left Widget -->		

	</div>
	<div class="col-lg-8">
               
                <!-- Start Article Center -->         
   
		@foreach( $articles as $article )
		<div class="card">
			<div class="card-header">
				<a href="#!">See Perfect World Private Server News</a>
			</div>
			<div class="card-body card-news">
				<div class="news-wrap">
					<div class="col-md-6 col-xs-8">	
						<a href="{{ url( '/news/post' ) . '/' . $article->slug }}">
							<b>{{ $article->title }}</b>
						</a>
					</div>
					<div class="col-md-2 col-xs-4">
						<a href="{{ url( '/news/category/' .  $article->category ) }}" class="kategori">
							{{ trans( 'news.category.' . $article->category ) }}
						</a>
					</div>
					<div class="col-md-4 col-xs-12">
						{{$article->created_at}}
					</div>
					<div class="col-md-12 col-xs-12 content">
						@if(!empty($article->coverimage))
						<img src="{{url($article->coverimage)}}" width="100%" style="margin-bottom:10px">
						@endif
						{!! str_limit(strip_tags($article->content), $limit = 200, $end = '...') !!}
					</div>
					<div class="col-md-12 col-xs-12 text-right">
						<a href="{{ url( '/news/post' ) . '/' . $article->slug }}">Read More</a> | 
						<a href="#!">Write Comment</a>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		<div class="text-center">
			{!! $articles->render() !!}
		</div>
	</div>
      
                <!-- End Article Center --> 

	<div class="col-lg-2">

                <!-- Start Right Widget -->

		<div class="card">
			<div class="card-header">
				<a href="#!">Menu</a>
			</div>
			<div class="card-body-right">
				<a href="./guide" class="right-menu">Guide</a>
				<a href="./news/post/panduan-download" class="right-menu">Download</a>
				<a href="./news/post/halaman-donasi" class="right-menu">Donasi</a>
				<a href="index.php?page=download" class="right-menu">Game Guides</a>
				<a href="index.php?page=download" class="right-menu">Helpdesk</a>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<a href="#!">Info Server</a>
			</div>
			<div class="card-body-server">
				<div class="server-status">
					Versi Game : <span class="online-text">Versi 1</span><br>
					Versi Server : <span class="online-text">v1.5.5</span><br>
					Version: Elysium
				</div>
				<div class="server-time">
					Perfect World<br>
					Private Server Orion
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<a href="#!">Staff</a>
			</div>
			<div class="card-body">
				<table>
					<tbody>
						<tr>
							<td>♂ Wahyu Suhandi</td>
							<td><span style="margin-left:5px;">Administrator</span></td>
						</tr>
						<tr>
							<td>♀ Echo</td>
							<td><span style="margin-left:5px;">Graphic Dev</span></td>
						</tr>
						<tr>
							<td>♂ Mada</td><td><span style="margin-left:5px;">Graphic Dev</span></td>
						</tr>
						<tr>
							<td>♂ Panda</td><td><span style="margin-left:5px;">Game Master</span></td>
						</tr>
						<tr>
							<td>♂ Nea</td><td><span style="margin-left:5px;">Game Master</span></td>
						</tr>
						<tr>
							<td>♂ Komachi</td>
							<td><span style="margin-left:5px;">Game Master</span></td>
						</tr>
						<tr>
							<td><span style="color:#005AFF">♀ Maat</span></td>
							<td><span style="margin-left:5px;"><span style="color:#005AFF">Game Master</span></span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
      
                <!-- End Right Widget -->

	</div>
</div>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0&appId=122861275004907&autoLogAppEvents=1"></script>
@endsection