<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\User\UserResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index() 
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $users = UserResource::collection($users)->resolve();
        return inertia('Chat/Index', compact('users'));
    }

    public function store(StoreRequest $request) 
    {
        $data = $request->validated();
        $usersIds = array_merge($data['users'], [auth()->id()]);
        sort($usersIds);
        $userIdsString = implode('-', $usersIds);

        try {
            DB::beginTransaction();

            $chat = Chat::firstOrCreate([
                'users' => $userIdsString
            ], [
                'title' => $data['title']
            ]);
    
            $chat->users()->sync($usersIds);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }

        $chat = ChatResource::make($chat)->resolve();
        return inertia('Chat/Show', compact('chat'));
    }
}
