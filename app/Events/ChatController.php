<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validasi input yang ketat
        $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $user = auth()->check() ? auth()->user()->name : 'Guest';
        $message = $request->message;

        // Memanggil event
        event(new MessageSent($user, $message));

        return response()->json(['status' => 'Pesan terkirim!']);
    }
}