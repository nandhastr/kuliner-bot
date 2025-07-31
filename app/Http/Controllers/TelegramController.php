<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $message = $request->input('message');

        if (isset($message['location'])) {
            return response()->json(['reply' => 'Searching nearby restaurants...']);
        } elseif (isset($message['text'])) {
            return response()->json(['reply' => 'Searching for: ' . $message['text']]);
        }

        return response()->json(['reply' => 'Unsupported message type']);
    }
}
