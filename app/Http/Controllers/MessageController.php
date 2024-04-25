<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\MessageStatus;
use Exception;

class MessageController extends Controller
{
    public function store(StoreRequest $request) {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $message = Message::create([
                'chat_id' => $data['chat_id'],
                'user_id' => auth()->id(),
                'body' => $data['body'],
            ]);
    
            foreach($data['user_ids'] as $userId) {
                MessageStatus::create([
                    'chat_id' => $data['chat_id'],
                    'message_id' => $message->id(),
                    'user_id' => $userId,
                ]);
            };

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => 'error', 
                'message' => $exception->getMessage(),
            ]);
        }

        return MessageResource::make($message)->resolve();
       
    }
}
