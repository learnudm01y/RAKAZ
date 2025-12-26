<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'ip_address',
        'session_id',
        'page_url',
        'user_id',
        'view_date',
    ];

    protected $casts = [
        'view_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // عدد مشاهدات الصفحات اليوم
    public static function todayCount()
    {
        return self::where('view_date', today())->count();
    }

    // عدد مشاهدات الصفحات هذا الشهر
    public static function thisMonthCount()
    {
        return self::whereMonth('view_date', now()->month)
            ->whereYear('view_date', now()->year)
            ->count();
    }

    // عدد مشاهدات الصفحات هذه السنة
    public static function thisYearCount()
    {
        return self::whereYear('view_date', now()->year)->count();
    }
}
