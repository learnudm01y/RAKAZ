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

        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            $query->where(function ($q) use ($like, $terms) {
                $q->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like);

                if (count($terms) >= 2) {
                    $first = $terms[0];
                    $last = $terms[count($terms) - 1];
                    $q->orWhere(function ($qq) use ($first, $last) {
                        $qq->where('first_name', 'like', '%' . $first . '%')
                            ->where('last_name', 'like', '%' . $last . '%');
                    })->orWhere(function ($qq) use ($first, $last) {
                        $qq->where('first_name', 'like', '%' . $last . '%')
                            ->where('last_name', 'like', '%' . $first . '%');
                    });
                }
            });
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20)->withQueryString();

        // Global stats (independent of pagination and the current filter)
        $statusCounts = ContactMessage::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->map(fn ($v) => (int) $v)
            ->all();

        $newCount = $statusCounts['new'] ?? 0;
        $readCount = $statusCounts['read'] ?? 0;
        $repliedCount = $statusCounts['replied'] ?? 0;
        $archivedCount = $statusCounts['archived'] ?? 0;

        return view('admin.customers.messages.index', compact(
            'messages',
            'newCount',
            'readCount',
            'repliedCount',
            'archivedCount'
        ));
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
