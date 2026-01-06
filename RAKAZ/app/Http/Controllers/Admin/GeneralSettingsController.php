<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    /**
     * Show the general settings page
     */
    public function index()
    {
        // Get all general settings
        $settings = [
            'tax_rate' => SiteSetting::where('key', 'tax_rate')->first()?->value_ar ?? '5',
            'tax_enabled' => SiteSetting::where('key', 'tax_enabled')->first()?->value_ar ?? '1',
            'currency_ar' => SiteSetting::where('key', 'currency')->first()?->value_ar ?? 'د.إ',
            'currency_en' => SiteSetting::where('key', 'currency')->first()?->value_en ?? 'AED',
            'free_shipping_threshold' => SiteSetting::where('key', 'free_shipping_threshold')->first()?->value_ar ?? '0',
            'default_shipping_cost' => SiteSetting::where('key', 'default_shipping_cost')->first()?->value_ar ?? '0',
        ];

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_enabled' => 'required|in:0,1',
            'currency_ar' => 'required|string|max:10',
            'currency_en' => 'required|string|max:10',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'default_shipping_cost' => 'nullable|numeric|min:0',
        ]);

        // Update or create tax_rate setting
        SiteSetting::updateOrCreate(
            ['key' => 'tax_rate'],
            [
                'value_ar' => $request->tax_rate,
                'value_en' => $request->tax_rate,
                'type' => 'number',
                'group' => 'general'
            ]
        );

        // Update or create tax_enabled setting
        SiteSetting::updateOrCreate(
            ['key' => 'tax_enabled'],
            [
                'value_ar' => $request->tax_enabled,
                'value_en' => $request->tax_enabled,
                'type' => 'boolean',
                'group' => 'general'
            ]
        );

        // Update or create currency setting
        SiteSetting::updateOrCreate(
            ['key' => 'currency'],
            [
                'value_ar' => $request->currency_ar,
                'value_en' => $request->currency_en,
                'type' => 'text',
                'group' => 'general'
            ]
        );

        // Update or create free_shipping_threshold setting
        SiteSetting::updateOrCreate(
            ['key' => 'free_shipping_threshold'],
            [
                'value_ar' => $request->free_shipping_threshold ?? '0',
                'value_en' => $request->free_shipping_threshold ?? '0',
                'type' => 'number',
                'group' => 'shipping'
            ]
        );

        // Update or create default_shipping_cost setting
        SiteSetting::updateOrCreate(
            ['key' => 'default_shipping_cost'],
            [
                'value_ar' => $request->default_shipping_cost ?? '0',
                'value_en' => $request->default_shipping_cost ?? '0',
                'type' => 'number',
                'group' => 'shipping'
            ]
        );

        // Clear any cached settings
        cache()->forget('site_settings');

        $message = app()->getLocale() == 'ar'
            ? 'تم حفظ الإعدادات بنجاح'
            : 'Settings saved successfully';

        return redirect()->route('admin.settings.general')->with('success', $message);
    }

    /**
     * Get tax rate as decimal (for calculations)
     */
    public static function getTaxRate(): float
    {
        $taxEnabled = SiteSetting::where('key', 'tax_enabled')->first()?->value_ar ?? '1';

        if ($taxEnabled != '1') {
            return 0;
        }

        $taxRate = SiteSetting::where('key', 'tax_rate')->first()?->value_ar ?? '5';
        return floatval($taxRate) / 100;
    }

    /**
     * Get tax rate as percentage (for display)
     */
    public static function getTaxRatePercentage(): float
    {
        $taxEnabled = SiteSetting::where('key', 'tax_enabled')->first()?->value_ar ?? '1';

        if ($taxEnabled != '1') {
            return 0;
        }

        return floatval(SiteSetting::where('key', 'tax_rate')->first()?->value_ar ?? '5');
    }

    /**
     * Check if tax is enabled
     */
    public static function isTaxEnabled(): bool
    {
        return (SiteSetting::where('key', 'tax_enabled')->first()?->value_ar ?? '1') == '1';
    }
}
