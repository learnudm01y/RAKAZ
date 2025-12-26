<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineUser extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'user_id',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // عدد المستخدمين المتصلين (نشطين خلال آخر 5 دقائق)
    public static function activeCount($minutes = 5)
    {
        return self::where('last_activity', '>=', now()->subMinutes($minutes))->count();
    }

    // عدد المستخدمين المسجلين المتصلين
    public static function registeredActiveCount($minutes = 5)
    {
        return self::where('last_activity', '>=', now()->subMinutes($minutes))
            ->whereNotNull('user_id')
            ->count();
    }

    // عدد الزوار (غير مسجلين)
    public static function guestActiveCount($minutes = 5)
    {
        return self::where('last_activity', '>=', now()->subMinutes($minutes))
            ->whereNull('user_id')
            ->count();
    }

    // تحديث أو إنشاء سجل المستخدم المتصل
    public static function updateActivity($sessionId, $ipAddress, $userId = null)
    {
        return self::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'ip_address' => $ipAddress,
                'user_id' => $userId,
                'last_activity' => now(),
            ]
        );
    }

    // تنظيف السجلات القديمة (أكثر من ساعة)
    public static function cleanOld($hours = 1)
    {
        return self::where('last_activity', '<', now()->subHours($hours))->delete();
    }
}
