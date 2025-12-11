<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Mail\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20);
        $newCount = ContactMessage::where('status', 'new')->count();

        return view('admin.customers.messages.index', compact('messages', 'newCount'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Mark as read if it's new
        if ($message->status === 'new') {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $message->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم تحديث الحالة بنجاح' : 'Status updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar' ? 'تم حذف الرسالة بنجاح' : 'Message deleted successfully'
        ]);
    }

    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'reply_message' => 'required|string|min:10'
        ]);

        $message = ContactMessage::findOrFail($id);

        try {
            // Prepare email data
            $messageData = [
                'customer_name' => $message->full_name,
                'reply_message' => $request->reply_message,
                'original_subject' => $message->subject,
                'original_message' => $message->message
            ];

            // Send email
            Mail::to($message->email)->send(new ContactReply($messageData));

            // Update message status to replied
            $message->update([
                'status' => 'replied'
            ]);

            return response()->json([
                'success' => true,
                'message' => app()->getLocale() == 'ar' ? 'تم إرسال الرد بنجاح' : 'Reply sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar' ? 'فشل في إرسال الرد: ' . $e->getMessage() : 'Failed to send reply: ' . $e->getMessage()
            ], 500);
        }
    }
}
