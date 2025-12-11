<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('order')->get();

        $grouped = $settings->groupBy('group');

        return view('admin.settings.index', compact('grouped'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'settings' => 'required|array',
                'settings.*.id' => 'required|exists:site_settings,id',
                'settings.*.value_ar' => 'nullable|string',
                'settings.*.value_en' => 'nullable|string',
            ]);

            foreach ($validated['settings'] as $settingData) {
                $setting = SiteSetting::find($settingData['id']);
                $setting->update([
                    'value_ar' => $settingData['value_ar'] ?? $setting->value_ar,
                    'value_en' => $settingData['value_en'] ?? $setting->value_en,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الإعدادات بنجاح'
            ]);

        } catch (\Exception $e) {
            \Log::error('Settings Update Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'فشل تحديث الإعدادات: ' . $e->getMessage()
            ], 500);
        }
    }
}
