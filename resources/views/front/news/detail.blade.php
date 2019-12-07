@extends( 'front.header' )

@section( 'content' )
    @foreach( $articles as $article )
        <div class="row news-detail-wrap">
			<div class="col-lg-12 col-md-12 col-xs-12">
				<div class="card">
					<div class="card-header-detail">
						<div class="row">
							<div class="col-lg-6 col-xs-12">
								<a href="{{ url( '/news/post' ) . '/' . $article->slug }}" class="article-title">
									<h2><b>{{ $article->title }}</b></h2>
								</a>
							</div>
							<div class="col-lg-6 col-xs-12">
								<a href="{{ url( '/news/category/' .  $article->category ) }}" class="article-kategori">
									{{ trans( 'news.category.' . $article->category ) }}
								</a>
							</div>
						</div>
					</div>                
					<div class="card-body-detail">
						@if(!empty($article->coverimage))
							<img src="{{url($article->coverimage)}}" width="100%" style="margin-bottom:10px">
						@endif
						{!! $article->content !!}
						<small class="bold">
							Writted by:
							<a href="{{ url( '/news/author/' .  $article->author ) }}">
								<span>{{ $article->author($article->author) }}</span>
							</a>
							on 
							{{ $article->created_at->toFormattedDateString() }}
						</small>
					</div>
				</div>
			</div>
		</div>
    @endforeach
@endsection