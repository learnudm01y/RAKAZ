<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterSection;
use App\Models\FooterItem;
use App\Models\FooterSocialLink;
use App\Models\FooterSetting;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * عرض صفحة إدارة الفوتر
     */
    public function index()
    {
        $sections = FooterSection::with('items')->ordered()->get();
        $socialLinks = FooterSocialLink::ordered()->get();
        $settings = FooterSetting::getAllSettings();
        $menus = Menu::active()->ordered()->get();
        // جلب التصنيفات الرئيسية فقط (بدون parent_id)
        $categories = Category::active()->whereNull('parent_id')->ordered()->get();
        // جلب صفحات الموقع
        $pages = Page::active()->ordered()->get();

        // قائمة الروابط المتاحة (الراوتات)
        $availableRoutes = $this->getAvailableRoutes();

        return view('admin.footer.index', compact(
            'sections',
            'socialLinks',
            'settings',
            'menus',
            'categories',
            'pages',
            'availableRoutes'
        ));
    }

    /**
     * الحصول على الراوتات المتاحة للروابط
     */
    private function getAvailableRoutes()
    {
        return [
            'home' => ['ar' => 'الرئيسية', 'en' => 'Home'],
            'about' => ['ar' => 'من نحن', 'en' => 'About Us'],
            'shop' => ['ar' => 'المتجر', 'en' => 'Shop'],
            'contact' => ['ar' => 'اتصل بنا', 'en' => 'Contact Us'],
            'privacy.policy' => ['ar' => 'سياسة الخصوصية', 'en' => 'Privacy Policy'],
            'orders.track' => ['ar' => 'تتبع الطلب', 'en' => 'Track Order'],
            'cart' => ['ar' => 'السلة', 'en' => 'Cart'],
            'wishlist' => ['ar' => 'المفضلة', 'en' => 'Wishlist'],
        ];
    }

    /**
     * إضافة قسم جديد
     */
    public function storeSection(Request $request)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'type' => 'required|in:links,menus,categories,custom',
        ]);

        $maxOrder = FooterSection::max('sort_order') ?? 0;

        FooterSection::create([
            'title' => [
                'ar' => $request->title_ar,
                'en' => $request->title_en,
            ],
            'type' => $request->type,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم إضافة القسم بنجاح'
                : 'Section added successfully');
    }

    /**
     * تحديث قسم
     */
    public function updateSection(Request $request, $id)
    {
        $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'type' => 'required|in:links,menus,categories,custom',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $section = FooterSection::findOrFail($id);

        $section->update([
            'title' => [
                'ar' => $request->title_ar,
                'en' => $request->title_en,
            ],
            'type' => $request->type,
            'sort_order' => $request->sort_order ?? $section->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث القسم بنجاح'
                : 'Section updated successfully');
    }

    /**
     * حذف قسم
     */
    public function destroySection($id)
    {
        $section = FooterSection::findOrFail($id);
        $section->delete();

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم حذف القسم بنجاح'
                : 'Section deleted successfully');
    }

    /**
     * تبديل حالة القسم
     */
    public function toggleSection($id)
    {
        $section = FooterSection::findOrFail($id);
        $section->update(['is_active' => !$section->is_active]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث حالة القسم بنجاح'
                : 'Section status updated successfully');
    }

    /**
     * إضافة عنصر جديد
     */
    public function storeItem(Request $request)
    {
        $request->validate([
            'footer_section_id' => 'required|exists:footer_sections,id',
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'link_type' => 'required|in:custom,route,menu,category,page',
            'link' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'menu_id' => 'nullable|exists:menus,id',
            'category_id' => 'nullable|exists:categories,id',
            'page_id' => 'nullable|exists:pages,id',
        ]);

        $maxOrder = FooterItem::where('footer_section_id', $request->footer_section_id)
            ->max('sort_order') ?? 0;

        FooterItem::create([
            'footer_section_id' => $request->footer_section_id,
            'title' => [
                'ar' => $request->title_ar ?? '',
                'en' => $request->title_en ?? '',
            ],
            'link_type' => $request->link_type,
            'link' => $request->link,
            'route_name' => $request->route_name,
            'menu_id' => $request->menu_id,
            'category_id' => $request->category_id,
            'page_id' => $request->page_id,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم إضافة العنصر بنجاح'
                : 'Item added successfully');
    }

    /**
     * تحديث عنصر
     */
    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'link_type' => 'required|in:custom,route,menu,category,page',
            'link' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'menu_id' => 'nullable|exists:menus,id',
            'category_id' => 'nullable|exists:categories,id',
            'page_id' => 'nullable|exists:pages,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $item = FooterItem::findOrFail($id);

        $item->update([
            'title' => [
                'ar' => $request->title_ar ?? '',
                'en' => $request->title_en ?? '',
            ],
            'link_type' => $request->link_type,
            'link' => $request->link,
            'route_name' => $request->route_name,
            'menu_id' => $request->menu_id,
            'category_id' => $request->category_id,
            'page_id' => $request->page_id,
            'sort_order' => $request->sort_order ?? $item->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث العنصر بنجاح'
                : 'Item updated successfully');
    }

    /**
     * حذف عنصر
     */
    public function destroyItem($id)
    {
        $item = FooterItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم حذف العنصر بنجاح'
                : 'Item deleted successfully');
    }

    /**
     * تبديل حالة العنصر
     */
    public function toggleItem($id)
    {
        $item = FooterItem::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث حالة العنصر بنجاح'
                : 'Item status updated successfully');
    }

    /**
     * إضافة رابط تواصل اجتماعي
     */
    public function storeSocialLink(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:500',
        ]);

        $maxOrder = FooterSocialLink::max('sort_order') ?? 0;

        FooterSocialLink::create([
            'platform' => $request->platform,
            'url' => $request->url,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم إضافة رابط التواصل بنجاح'
                : 'Social link added successfully');
    }

    /**
     * تحديث رابط تواصل اجتماعي
     */
    public function updateSocialLink(Request $request, $id)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $link = FooterSocialLink::findOrFail($id);

        $link->update([
            'platform' => $request->platform,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? $link->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث رابط التواصل بنجاح'
                : 'Social link updated successfully');
    }

    /**
     * حذف رابط تواصل اجتماعي
     */
    public function destroySocialLink($id)
    {
        $link = FooterSocialLink::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم حذف رابط التواصل بنجاح'
                : 'Social link deleted successfully');
    }

    /**
     * تبديل حالة ظهور رابط التواصل
     */
    public function toggleSocialLink($id)
    {
        $link = FooterSocialLink::findOrFail($id);
        $link->update(['is_active' => !$link->is_active]);

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث حالة رابط التواصل بنجاح'
                : 'Social link status updated successfully');
    }

    /**
     * تحديث الإعدادات العامة
     */
    public function updateSettings(Request $request)
    {
        $settingsToUpdate = [
            'newsletter_title' => [
                'ar' => $request->input('newsletter_title_ar'),
                'en' => $request->input('newsletter_title_en'),
            ],
            'newsletter_placeholder' => [
                'ar' => $request->input('newsletter_placeholder_ar'),
                'en' => $request->input('newsletter_placeholder_en'),
            ],
            'newsletter_button' => [
                'ar' => $request->input('newsletter_button_ar'),
                'en' => $request->input('newsletter_button_en'),
            ],
            'social_title' => [
                'ar' => $request->input('social_title_ar'),
                'en' => $request->input('social_title_en'),
            ],
            'apps_title' => [
                'ar' => $request->input('apps_title_ar'),
                'en' => $request->input('apps_title_en'),
            ],
            'customer_service_label' => [
                'ar' => $request->input('customer_service_label_ar'),
                'en' => $request->input('customer_service_label_en'),
            ],
            'customer_service_phone' => $request->input('customer_service_phone'),
            'whatsapp_label' => [
                'ar' => $request->input('whatsapp_label_ar'),
                'en' => $request->input('whatsapp_label_en'),
            ],
            'whatsapp_number' => $request->input('whatsapp_number'),
            'copyright_text' => [
                'ar' => $request->input('copyright_ar'),
                'en' => $request->input('copyright_en'),
            ],
            'copyright_ar' => $request->input('copyright_ar'),
            'copyright_en' => $request->input('copyright_en'),
            'show_newsletter' => $request->has('show_newsletter'),
            'show_social_links' => $request->has('show_social_links'),
            'show_apps' => $request->has('show_apps_section'),
            'show_apps_section' => $request->has('show_apps_section'),
            'show_contact_info' => $request->has('show_contact_info'),
        ];

        foreach ($settingsToUpdate as $key => $value) {
            FooterSetting::set($key, $value);
        }

        return redirect()->route('admin.footer.index')
            ->with('success', app()->getLocale() == 'ar'
                ? 'تم تحديث الإعدادات بنجاح'
                : 'Settings updated successfully');
    }

    /**
     * تحديث ترتيب الأقسام
     */
    public function reorderSections(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:footer_sections,id',
        ]);

        foreach ($request->order as $index => $id) {
            FooterSection::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * تحديث ترتيب العناصر
     */
    public function reorderItems(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:footer_items,id',
        ]);

        foreach ($request->order as $index => $id) {
            FooterItem::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
