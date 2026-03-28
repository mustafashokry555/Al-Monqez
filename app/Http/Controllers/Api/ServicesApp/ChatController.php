<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\FileStorage;
use App\Http\Requests\Api\ServicesApp\Chats\SendMessageRequest;
use App\Http\Resources\ServicesApp\ChatResource;
use App\Models\Chat;
use App\Models\File;
use App\Models\Message;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    use ApiResponse, FileStorage;

    public function show($order_id = null)
    {
        $chat = Chat::query()->select('id', 'created_at')
            ->with([
                'messages' => function ($query) {
                    $query->select(
                        'messages.id',
                        'messages.chat_id',
                        'messages.user_id',
                        'users.name AS user_name',
                        'users.image AS user_image',
                        'messages.content',
                        'messages.read',
                        'messages.created_at'
                    )
                        ->leftJoin('users', 'messages.user_id', '=', 'users.id')
                        ->orderBy('created_at', 'asc');
                },
                'messages.files' => function ($query) {
                    $query->select(
                        'files.id',
                        'files.message_id',
                        'files.path',
                        'files.created_at'
                    );
                }
            ]);

        $setting = null;
        if (is_null($order_id)) {
            $chat = $chat->whereNull('order_id')->where('user_id', auth()->id())->firstOrFail();
            $setting = Setting::select('logo')->first();
        } else {
            $chat = $chat->where('order_id', $order_id)->whereNull('user_id')->firstOrFail();
        }

        $length = count($chat->messages);
        if ($length > 0) {
            Message::where([
                ['id', '<=', $chat->messages[$length - 1]->id],
                ['user_id', '!=', auth()->id()],
                ['read', 0]
            ])->update(['read' => 1]);
        }

        return $this->apiResponse(200, 'chat', null, new ChatResource($chat, ['setting' => $setting]));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function sendMessage(SendMessageRequest $request)
    {
        $search = [];
        if ($request->order_id) {
            $search['order_id'] = $request->order_id;
        } else {
            $search['user_id'] = auth()->id();
        }

        $chat = Chat::firstOrCreate($search);

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
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

        if ($request->order_id) {
            $order = Order::select('id', 'client_id', 'worker_id', 'status')->findOrFail($request->order_id);
            $userId = ($order->client_id == auth()->id()) ? $order->worker_id : $order->client_id;

            $devicesTokens = User::select('users.device_token')
                ->where('id', $userId)
                ->whereNotNull('device_token')
                ->pluck('device_token')
                ->all();

            $paths = array_map(function ($path) {
                return Storage::url($path);
            }, $paths);

            $notificationService = new FirebaseService();
            $notificationService->notify(
                __('messages.new_message'),
                __('messages.new_message_from', ['USER' => auth()->user()->name, 'ORDER_ID' => $order->id]),
                $devicesTokens,
                [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'message_id' => $message->id,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_image' => auth()->user()->imageLink,
                    'content' => $message->content,
                    'files' => implode(',', $paths),
                    'created_at' => $message->created_at,
                    'navigation' => 'chat'
                ]
            );
        }

        return $this->apiResponse(200, __('messages.message_sent'));
    }
}
