<?php

namespace Chatty\Http\Controllers;

use Auth;
use Chatty\Models\User;
use Chatty\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
	public function postStatus(Request $request){
		$this->validate($request, [
			'status' => 'required|max:1000',
		]);

		Auth::user()->statuses()->create([
			'body' => $request->input('status'),
		]);

		return redirect()->route('home')
						 ->with('info', 'Status posted.');
	}

	public function postReply(Request $request, $id){
		$this->validate($request, [
			"reply-{$id}" => 'required|max:1000',
		],[
			'required' => 'The reply body is required.',
		]);

		$status = Status::notReply()->find($id);

		if(!$status){
			return redirect()->route('home');
		}

		if(!Auth::user()->isFriendsWith($status->user) && Auth::user()-> id !== $status->user->id){
			return redirect()->route('home');
		}

		$reply = Status::create([
			'body' => $request->input("reply-{$id}"),
			'user_id' => Auth::user()->id,
		]);

		$status->replies()->save($reply);

		return redirect()->back();
	}

	public function getLike($id){
		$status = Status::find($id);

		if(!$status){
			return redirect()->route('home');
		}

		if(!Auth::user()->isFriendsWith($status->user) && Auth::user()-> id === $status->user->id){
			return redirect()->route('home');
		}

		if(Auth::user()->hasLikedStatus($status)){
			return redirect()->back();
		}

		$like = $status->likes()->create([
			'user_id' => Auth::user()->id,
		]);

		Auth::user()->likes()->save($like);

		return redirect()->back();
	}
}