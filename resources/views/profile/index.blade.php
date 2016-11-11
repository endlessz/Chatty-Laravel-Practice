@extends('templates.default')

@section('content')
	<div class="row">
		<div class="col-lg-5">
			@include('user.patials.userblock')
			<hr/>

			@if(!$statuses->count())
				<p>{{$user->getNameOrUsername() }} has not posted anything.</p>
			@else
				@foreach($statuses as $status)
					<div class="media">
					    <a class="pull-left" href="{{ route('profile.index', ['username' => $status->user->username])}}">
					        <img class="media-object" alt="" src="{{ $status->user->getAvatarUrl() }}">
					    </a>
					    <div class="media-body">
					        <h4 class="media-heading"><a href="{{ route('profile.index', ['username' => $status->user->username])}}">{{ $status->user->getNameOrUsername() }}</a></h4>
					        <p>{{ $status->body }}</p>
					        <ul class="list-inline">
					            <li>{{ $status->created_at->diffForHumans()}}</li>
					            <li>{{ $status->likes->count() }} {{ str_plural('like', $status->likes->count())}}</li>
					        </ul>

							@foreach($status->replies as $reply)
						        <div class="media">
						            <a class="pull-left" href="{{ route('profile.index', ['username' => $reply->user->username])}}">
						                <img class="media-object" alt="" src="{{ $reply->user->getAvatarUrl() }}">
						            </a>
						            <div class="media-body">
						                <h5 class="media-heading"><a href="{{ route('profile.index', ['username' => $reply->user->username])}}">{{ $reply->user->getNameOrUsername() }}</a></h5>
						                <p>{{ $reply->body }}</p>
						                <ul class="list-inline">
						                    <li>{{ $reply->created_at->diffForHumans() }}</li>
						                    @if($reply->user->id !== Auth::user()->id)
						                    	<li><a href="{{ route('status.like', ['id' => $reply->id])}}">Like</a></li>
						                    @endif
						                    <li>{{ $reply->likes->count() }} {{ str_plural('like', $reply->likes->count())}}</li>
						                </ul>
						            </div>
						        </div>
						    @endforeach

							@if($authUserIsFriend || Auth::user()->id === $status->user->id)
						        <form role="form" action="{{ route('status.reply', ['id' => $status->id]) }}" method="post">
						            <div class="form-group{{ $errors->has("reply-{$status->id}") ? ' has-error': ''}}">
						                <textarea name="reply-{{ $status->id }}" class="form-control" rows="2" placeholder="Reply to this status"></textarea>
										
										@if($errors->has("reply-{$status->id}"))
											<span class="help-block">{{ $errors->first("reply-{$status->id}") }}</span>
										@endif
						            </div>
						            <input type="submit" value="Reply" class="btn btn-default btn-sm">
						            {{ csrf_field() }}
						        </form>
						    @endif
					    </div>
					</div>
				@endforeach
{{-- 
				{{ $statuses->render() }} --}}
			@endif

		</div>
		<div class="col-lg-4 col-lg-offset-3">
			@if (Auth::user() -> hasFriendRequestsPending($user))
				<p>Waiting for {{ $user->getNameOrUsername() }} to accept your request.</p>
			@elseif (Auth::user() -> hasFriendRequestsReceived($user))
				<a href="{{ route('friends.accept', ['username' => $user->username])}}" class="btn btn-primary">Accept friend request</a>
			@elseif (Auth::user() -> isFriendsWith($user))
				<p>You are now friends with {{ $user->getNameOrUsername() }}</p>
			@elseif (Auth::user()->id !== $user->id)
				<a href="{{ route('friends.add', ['username' => $user->username])}}" class="btn btn-primary">Add friend</a>
			@endif


			<h4>{{ $user->getNameOrUsername() }}'s friends.</h4>

			@if (!$user -> friends() -> count())
				<p>{{ $user->getNameOrUsername() }} has no friends.</p>
			@else
				@foreach($user -> friends() as $user)
					@include('user.patials.userblock')
				@endforeach
			@endif
		</div>
	</div>
@stop
