<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'user_id',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // عدد الزوار الفريدين اليوم (كل IP = زائر واحد)
    public static function todayCount()
    {
        return self::where('visit_date', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    // عدد الزوار الفريدين هذا الشهر
    public static function thisMonthCount()
    {
        return self::whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    // عدد الزوار الفريدين هذه السنة
    public static function thisYearCount()
    {
        return self::whereYear('visit_date', now()->year)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    // عدد الزوار الفريدين (alias للتوافق)
    public static function uniqueTodayCount()
    {
        return self::todayCount();
    }
}
