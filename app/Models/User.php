<?php

namespace Chatty\Models;

use Chatty\Models\Status;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 
        'email', 
        'password',
        'first_name',
        'last_name',
        'location',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function getName()
    {
        if($this->first_name && $this->last_name){
            return "{$this->first_name} {$this->last_name}";
        }

        if($this->first_name){
            return "{$this->first_name}";
        }

        return null;
    }

    public function getNameOrUsername(){
        return $this->getName() ?: $this->username;
    }

    public function getFirstName(){
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl() {
        return "https://www.gravatar.com/avatar/{{ md5($this->email) }}?d=mm&s=40";
    }

    public function statuses(){
        return $this->hasMany('Chatty\Models\Status', 'user_id');
    }

    public function likes(){
        return $this->hasMany('Chatty\Models\Like', 'user_id');
    }

    public function friendsOfMine(){
        return $this->belongsToMany('Chatty\Models\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendsOf(){
        return $this->belongsToMany('Chatty\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends(){
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()
                        ->merge($this->friendsOf()->wherePivot('accepted', true)->get());
    }

    public function friendRequests(){
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }

    public function friendRequestsPending(){
        return $this->friendsOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestsPending(User $user){
        return (bool) $this->friendRequestsPending()->where('id', $user->id)-> count();
    }

    public function hasFriendRequestsReceived(User $user){
        return (bool) $this->friendRequests()->where('id', $user->id)-> count();
    }

    public function addFriend(User $user){
        $this->friendsOf()->attach($user->id);
    }

    public function acceptFriendRequest(User $user){
        $this->friendRequests()->where('id', $user->id)->first()->pivot->update([
            'accepted' => true,
        ]);
    }

    public function isFriendsWith(User $user){
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

    public function hasLikedStatus(Status $status){
        return (bool) $status->likes->where('user_id', $this->id)->count();
    }
}