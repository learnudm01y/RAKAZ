<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$page = App\Models\ContactPage::first();

if ($page) {
    $page->update([
        'phone' => '800717171',
        'email' => 'info@rakaz.com',
        'whatsapp' => '+971 55 300 7879',
        'address_ar' => "دبي مول، الطابق الأرضي\nدبي، الإمارات العربية المتحدة",
        'address_en' => "Dubai Mall, Ground Floor\nDubai, United Arab Emirates",
        'working_hours_ar' => '<p><strong>الأحد - الخميس:</strong> 10:00 صباحاً - 10:00 مساءً</p><p><strong>الجمعة - السبت:</strong> 10:00 صباحاً - 11:00 مساءً</p>',
        'working_hours_en' => '<p><strong>Sunday - Thursday:</strong> 10:00 AM - 10:00 PM</p><p><strong>Friday - Saturday:</strong> 10:00 AM - 11:00 PM</p>',
        'additional_info_ar' => '<p>يمكنكم زيارتنا في فرعنا الرئيسي في دبي مول أو التواصل معنا عبر أي من القنوات المتاحة. نحن دائماً في خدمتكم.</p>',
        'additional_info_en' => '<p>You can visit us at our main branch in Dubai Mall or contact us through any available channels. We are always at your service.</p>',
    ]);

    echo "Contact page updated successfully!\n";
    echo "Phone: " . $page->phone . "\n";
    echo "Email: " . $page->email . "\n";
    echo "WhatsApp: " . $page->whatsapp . "\n";
} else {
    echo "No contact page found in database.\n";
}
