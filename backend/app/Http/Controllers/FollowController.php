<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Activity;

use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }
    
        if (auth()->user()->followings->contains($user->id)) {
            return response()->json(['error' => 'You already followed this user'], 400);
        }
    
        auth()->user()->followings()->attach($user->id);
        Activity::create([
            "user_id" => auth()->user()->id,
            "description" => "Followed user with id: {$user->id}",
            "activitiable_id" => $user->id,
            "activitiable_type" => "App\Models\Follow"
        ]);
    
        return response()->json(['message' => 'Successfully followed user']);
    }
    
    public function unfollow(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return response()->json(['error' => 'You cannot unfollow yourself'], 400);
        }
    
        if (!auth()->user()->followings->contains($user->id)) {
            return response()->json(['error' => 'You already unfollowed this user'], 400);
        }
    
        auth()->user()->followings()->detach($user->id);
        Activity::create([
            "user_id" => auth()->user()->id,
            "description" => "Unfollowed user with id: {$user->id}",
            "activitiable_id" => $user->id,
            "activitiable_type" => "App\Models\Follow"
        ]);
    
        return response()->json(['message' => 'Successfully unfollowed user']);
    }
    
    
}