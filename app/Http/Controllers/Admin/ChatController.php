<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\EmployeeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $current_user_id = Auth::id();

        // استرجاع جميع المستخدمين الآخرين النشطين في نفس الشركة
        $users = Admin::where('company_id', $company_id)
            ->where('id', '!=', $current_user_id)
            ->where('status', 1)
            ->get();

        foreach ($users as $user) {
            $user->unread_messages_count = EmployeeMessage::where('company_id', $company_id)
                ->where('sender_id', $user->id)
                ->where('receiver_id', $current_user_id)
                ->where('is_read', 0)
                ->count();
            
            // استرجاع آخر رسالة بين المستخدمين للعرض
            $user->last_message = EmployeeMessage::where('company_id', $company_id)
                ->where(function($q) use ($current_user_id, $user) {
                    $q->where('sender_id', $current_user_id)->where('receiver_id', $user->id);
                })
                ->orWhere(function($q) use ($current_user_id, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $current_user_id);
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // ترتيب المستخدمين بحيث يظهر من لديه رسائل غير مقروءة أو رسائل أحدث في الأعلى
        $users = $users->sort(function($a, $b) {
            if ($a->unread_messages_count != $b->unread_messages_count) {
                return $b->unread_messages_count <=> $a->unread_messages_count;
            }
            $aTime = $a->last_message ? $a->last_message->created_at->timestamp : 0;
            $bTime = $b->last_message ? $b->last_message->created_at->timestamp : 0;
            return $bTime <=> $aTime;
        });

        return view('admin.chats.index', compact('users'));
    }

    public function history(Request $request, $receiver_id)
    {
        $company_id = Auth::user()->company_id;
        $current_user_id = Auth::id();

        // تحديث الرسائل الواردة من هذا المستخدم لتصبح مقروءة
        EmployeeMessage::where('company_id', $company_id)
            ->where('sender_id', $receiver_id)
            ->where('receiver_id', $current_user_id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        // جلب الرسائل المتبادلة
        $messages = EmployeeMessage::with(['sender', 'receiver'])
            ->where('company_id', $company_id)
            ->where(function($q) use ($current_user_id, $receiver_id) {
                $q->where('sender_id', $current_user_id)->where('receiver_id', $receiver_id);
            })
            ->orWhere(function($q) use ($current_user_id, $receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', $current_user_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $html = view('admin.chats.messages-stream', compact('messages'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:admins,id',
            'message' => 'required|string',
        ]);

        $company_id = Auth::user()->company_id;
        $current_user_id = Auth::id();

        $message = EmployeeMessage::create([
            'company_id' => $company_id,
            'sender_id' => $current_user_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function unreadCount(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $current_user_id = Auth::id();

        $unreadCount = EmployeeMessage::where('company_id', $company_id)
            ->where('receiver_id', $current_user_id)
            ->where('is_read', 0)
            ->count();

        // جلب آخر 5 رسائل غير مقروءة مع اسم المرسل
        $latestUnread = EmployeeMessage::with('sender')
            ->where('company_id', $company_id)
            ->where('receiver_id', $current_user_id)
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $html = view('admin.chats.navbar-dropdown-items', compact('latestUnread', 'unreadCount'))->render();

        return response()->json([
            'unread_count' => $unreadCount,
            'html' => $html
        ]);
    }
}
