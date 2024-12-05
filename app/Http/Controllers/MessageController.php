<?php

// app/Http/Controllers/MessageController.php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Helpers\EncryptionHelper;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        // create key
        $dateNow = now();
        $key = EncryptionHelper::encrypt($request->recipient . $dateNow, env('KEY_SEED'));

        $encryptedText = EncryptionHelper::encrypt($request->text, $key);

        $message = Message::create([
            'text' => $encryptedText,
            'recipient' => $request->recipient,
            'created_at' => $dateNow,
            'expiry' => $request->expiry,
        ]);

        return response()->json(['id' => $message->id, 'key' => $key]);
    }

    public function read(Request $request, $id)
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['error' => 'No Access'], 403);
        }

        $key = $request->key;

        // check key first
        $decryptedKey = EncryptionHelper::decrypt($key, env('KEY_SEED'));
        if ($decryptedKey === false) {
            return response()->json(['error' => 'No Access'], 403);
        }

        $decryptedText = EncryptionHelper::decrypt($message->text, $key);

        if ($message->expiry === 'read_once') {
            $message->delete();
        }

        return response()->json(['text' => $decryptedText]);
    }
}
