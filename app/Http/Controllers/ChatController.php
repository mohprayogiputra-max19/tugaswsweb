<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        try {
            event(new MessageSent($request->user()->name, $validated['message']));
        } catch (BroadcastException $exception) {
            Log::error('Chat message could not be broadcast.', [
                'user_id' => $request->user()->id,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Server Reverb tidak berjalan atau tidak dapat dijangkau. Jalankan php artisan reverb:start.',
            ], 503);
        }

        return response()->json(['status' => 'Pesan terkirim!']);
    }
}
