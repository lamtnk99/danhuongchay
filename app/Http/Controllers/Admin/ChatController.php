<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $chats = ChatSession::query()
            ->withCount(['messages as unread_count' => fn ($query) => $query
                ->where('sender', 'visitor')
                ->where('is_read', false)])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query->where('visitor_name', 'like', '%'.$request->q.'%')
                        ->orWhere('phone', 'like', '%'.$request->q.'%')
                        ->orWhere('email', 'like', '%'.$request->q.'%');
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('last_message_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.chats.index', compact('chats'));
    }

    public function show(ChatSession $chat): View
    {
        $chat->messages()
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chat->load(['messages' => fn ($query) => $query->oldest()]);

        return view('admin.chats.show', compact('chat'));
    }

    public function messages(ChatSession $chat): JsonResponse
    {
        $chat->messages()
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()
            ->oldest()
            ->get()
            ->map(fn ($message): array => [
                'id' => $message->id,
                'sender' => $message->sender,
                'sender_name' => $message->sender_name ?: $message->sender,
                'message' => $message->message,
                'created_at' => $message->created_at->format('H:i'),
            ]);

        return response()->json([
            'status' => $chat->status,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, ChatSession $chat): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1200'],
        ]);

        $chat->messages()->create([
            'sender' => 'admin',
            'sender_name' => auth()->user()->name,
            'message' => $data['message'],
            'is_read' => true,
        ]);

        $chat->update([
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        return back()->with('success', 'Đã gửi phản hồi cho khách.');
    }

    public function update(Request $request, ChatSession $chat): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,pending,closed'],
        ]);

        $chat->update($data);

        return back()->with('success', 'Đã cập nhật trạng thái chat.');
    }

    public function destroy(ChatSession $chat): RedirectResponse
    {
        $chat->delete();

        return redirect()->route('admin.chats.index')->with('success', 'Đã xóa hội thoại.');
    }
}
