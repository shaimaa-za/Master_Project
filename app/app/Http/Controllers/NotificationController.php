<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * عرض صفحة الإشعارات
     */
    public function index()
    {
        return view('notifications'); // تأكد أن اسم الملف هو notifications/index.blade.php
    }

    /**
     * وضع علامة "مقروء" على جميع الإشعارات
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    /**
     * وضع علامة "مقروء" على إشعار معين
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }
}
