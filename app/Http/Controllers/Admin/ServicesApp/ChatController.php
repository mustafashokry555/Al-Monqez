<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Admin\ServicesApp\Chats\SendMessageRequest;
use App\Models\Chat;
use App\Models\File;
use App\Models\Message;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    use FileStorage;

    public function index()
    {
        $users = User::select('id', 'role_id', 'name', 'phone', 'image')
            ->withCount('new_messages')
            ->whereIn('role_id', [3, 4])
            ->orderBy('new_messages_count', 'DESC')
            ->paginate(10);

        return view('admin.services-app.chats.index', compact('users'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function show($id)
    {
        $user = User::select('id', 'role_id', 'name', 'image')
            ->with([
                'messages' => function ($query) {
                    $query->select('messages.id', 'messages.chat_id', 'messages.user_id', 'messages.content', 'messages.read', 'messages.created_at')
                        ->orderBy('created_at', 'ASC');
                },
                'messages.files' => function ($query) {
                    $query->select(
                        'files.message_id',
                        'files.path'
                    );
                }
            ])
            ->FindOrFail($id);

        $length = count($user->messages);
        if ($length > 0) {
            Message::where([
                ['id', '<=', $user->messages[$length - 1]->id],
                ['read', 0]
            ])
                ->whereNotNull('user_id')
                ->update(['read' => 1]);
        }

        return view('admin.services-app.chats.show', compact('user'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function sendMessage(SendMessageRequest $request)
    {
        $chat = Chat::firstOrCreate([
            'user_id' => $request->user_id
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'content' => $request->content
        ]);

        $paths = [];
        if ($request->file('files') && count($request->file('files')) > 0) {
            $data = [];
            $paths = $this->uploadMultipleFiles($request, 'chat-files', 'files');

            foreach ($paths as $path) {
                $data[] = [
                    'message_id' => $message->id,
                    'path' => $path,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            File::insert($data);
        }

        $devicesTokens = User::select('users.device_token')
            ->where('id', $request->user_id)
            ->whereNotNull('device_token')
            ->pluck('device_token')
            ->all();

        $paths = array_map(function ($path) {
            return Storage::url($path);
        }, $paths);

        $notificationService = new FirebaseService();
        $notificationService->notify(
            __('messages.new_message'),
            __('messages.new_message_from_management'),
            $devicesTokens,
            [
                'message_id' => $message->id,
                'user_id' => auth()->id(),
                'content' => $message->content,
                'files' => implode(',', $paths),
                'created_at' => $message->created_at,
                'navigation' => 'chat'
            ]
        );

        return redirect()->back();
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function checkNewMessage(Request $request)
    {
        $hasNew = Message::where('read', 0)
            ->join('chats', 'messages.chat_id', '=', 'chats.id')
            ->where([
                ['chats.user_id', $request->user_id],
                ['messages.user_id', $request->user_id]
            ])
            ->exists();

        return response()->json($hasNew);
    }
}
