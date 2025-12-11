<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about-us',
                'title_ar' => 'من نحن',
                'title_en' => 'About Us',
                'content_ar' => '<h2>من نحن</h2>
<p>مرحباً بكم في ركاز، وجهتكم الموثوقة للتسوق الإلكتروني في المملكة العربية السعودية.</p>

<h3>رؤيتنا</h3>
<p>نسعى لأن نكون المنصة الأولى للتسوق الإلكتروني في المنطقة، من خلال تقديم تجربة تسوق استثنائية ومنتجات عالية الجودة.</p>

<h3>مهمتنا</h3>
<p>توفير أفضل المنتجات بأسعار تنافسية، مع خدمة عملاء متميزة وتوصيل سريع وآمن.</p>

<h3>قيمنا</h3>
<ul>
    <li>الجودة في كل شيء نقدمه</li>
    <li>الأمانة والشفافية مع عملائنا</li>
    <li>الابتكار المستمر في خدماتنا</li>
    <li>رضا العملاء هو هدفنا الأول</li>
</ul>',
                'content_en' => '<h2>About Us</h2>
<p>Welcome to Rakaz, your trusted destination for online shopping in Saudi Arabia.</p>

<h3>Our Vision</h3>
<p>We strive to be the leading e-commerce platform in the region by providing an exceptional shopping experience and high-quality products.</p>

<h3>Our Mission</h3>
<p>To provide the best products at competitive prices, with outstanding customer service and fast, secure delivery.</p>

<h3>Our Values</h3>
<ul>
    <li>Quality in everything we offer</li>
    <li>Honesty and transparency with our customers</li>
    <li>Continuous innovation in our services</li>
    <li>Customer satisfaction is our priority</li>
</ul>',
                'meta_description_ar' => 'تعرف على ركاز، منصة التسوق الإلكتروني الرائدة في المملكة العربية السعودية',
                'meta_description_en' => 'Learn about Rakaz, the leading e-commerce platform in Saudi Arabia',
                'meta_keywords_ar' => 'من نحن, ركاز, تسوق إلكتروني, السعودية',
                'meta_keywords_en' => 'about us, rakaz, e-commerce, saudi arabia',
                'status' => 'active',
                'order' => 1,
            ],
            [
                'slug' => 'privacy-policy',
                'title_ar' => 'سياسة الخصوصية',
                'title_en' => 'Privacy Policy',
                'content_ar' => '<h2>سياسة الخصوصية</h2>
<p>آخر تحديث: ' . date('Y/m/d') . '</p>

<h3>المقدمة</h3>
<p>نحن في ركاز نلتزم بحماية خصوصيتك وأمن معلوماتك الشخصية. توضح هذه السياسة كيفية جمع واستخدام وحماية بياناتك.</p>

<h3>المعلومات التي نجمعها</h3>
<p>نقوم بجمع المعلومات التالية:</p>
<ul>
    <li>المعلومات الشخصية: الاسم، البريد الإلكتروني، رقم الهاتف، العنوان</li>
    <li>معلومات الدفع: يتم معالجتها بشكل آمن من خلال بوابات دفع موثوقة</li>
    <li>معلومات التصفح: عنوان IP، نوع المتصفح، سجل التصفح</li>
</ul>

<h3>كيف نستخدم معلوماتك</h3>
<ul>
    <li>معالجة الطلبات وإتمام عمليات الشراء</li>
    <li>التواصل معك بخصوص طلباتك</li>
    <li>تحسين خدماتنا وتجربة التسوق</li>
    <li>إرسال العروض والتحديثات (بموافقتك)</li>
</ul>

<h3>حماية البيانات</h3>
<p>نستخدم أحدث تقنيات التشفير والأمان لحماية بياناتك الشخصية من الوصول غير المصرح به.</p>

<h3>حقوقك</h3>
<p>لديك الحق في:</p>
<ul>
    <li>الوصول إلى بياناتك الشخصية</li>
    <li>تصحيح أو تحديث بياناتك</li>
    <li>حذف حسابك وبياناتك</li>
    <li>الاعتراض على استخدام بياناتك</li>
</ul>

<h3>التواصل معنا</h3>
<p>إذا كان لديك أي استفسارات حول سياسة الخصوصية، يرجى التواصل معنا عبر البريد الإلكتروني: privacy@rakaz.com</p>',
                'content_en' => '<h2>Privacy Policy</h2>
<p>Last updated: ' . date('Y/m/d') . '</p>

<h3>Introduction</h3>
<p>At Rakaz, we are committed to protecting your privacy and the security of your personal information. This policy explains how we collect, use, and protect your data.</p>

<h3>Information We Collect</h3>
<p>We collect the following information:</p>
<ul>
    <li>Personal information: Name, email, phone number, address</li>
    <li>Payment information: Processed securely through trusted payment gateways</li>
    <li>Browsing information: IP address, browser type, browsing history</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
    <li>Process orders and complete purchases</li>
    <li>Communicate with you about your orders</li>
    <li>Improve our services and shopping experience</li>
    <li>Send offers and updates (with your consent)</li>
</ul>

<h3>Data Protection</h3>
<p>We use the latest encryption and security technologies to protect your personal data from unauthorized access.</p>

<h3>Your Rights</h3>
<p>You have the right to:</p>
<ul>
    <li>Access your personal data</li>
    <li>Correct or update your data</li>
    <li>Delete your account and data</li>
    <li>Object to the use of your data</li>
</ul>

<h3>Contact Us</h3>
<p>If you have any questions about our privacy policy, please contact us at: privacy@rakaz.com</p>',
                'meta_description_ar' => 'اطلع على سياسة الخصوصية الخاصة بمنصة ركاز وكيفية حماية بياناتك الشخصية',
                'meta_description_en' => 'Review Rakaz platform privacy policy and how we protect your personal data',
                'meta_keywords_ar' => 'سياسة الخصوصية, حماية البيانات, أمان المعلومات',
                'meta_keywords_en' => 'privacy policy, data protection, information security',
                'status' => 'active',
                'order' => 2,
            ],
            [
                'slug' => 'contact-us',
                'title_ar' => 'تواصل معنا',
                'title_en' => 'Contact Us',
                'content_ar' => '<h2>تواصل معنا</h2>
<p>نحن هنا لخدمتك! يسعدنا تلقي استفساراتك واقتراحاتك في أي وقت.</p>

<h3>معلومات الاتصال</h3>
<p><strong>العنوان:</strong><br>
المملكة العربية السعودية<br>
الرياض - حي النخيل<br>
طريق الملك فهد</p>

<p><strong>البريد الإلكتروني:</strong><br>
support@rakaz.com<br>
info@rakaz.com</p>

<p><strong>الهاتف:</strong><br>
+966 11 234 5678<br>
واتساب: +966 50 123 4567</p>

<h3>أوقات العمل</h3>
<p>السبت - الخميس: 9:00 صباحاً - 6:00 مساءً<br>
الجمعة: مغلق</p>

<h3>خدمة العملاء</h3>
<p>فريق خدمة العملاء لدينا متاح للرد على استفساراتك ومساعدتك في:</p>
<ul>
    <li>تتبع الطلبات</li>
    <li>الاستفسار عن المنتجات</li>
    <li>الإرجاع والاستبدال</li>
    <li>المشاكل التقنية</li>
    <li>أي استفسارات أخرى</li>
</ul>',
                'content_en' => '<h2>Contact Us</h2>
<p>We are here to serve you! We are happy to receive your inquiries and suggestions at any time.</p>

<h3>Contact Information</h3>
<p><strong>Address:</strong><br>
Saudi Arabia<br>
Riyadh - Al Nakheel District<br>
King Fahd Road</p>

<p><strong>Email:</strong><br>
support@rakaz.com<br>
info@rakaz.com</p>

<p><strong>Phone:</strong><br>
+966 11 234 5678<br>
WhatsApp: +966 50 123 4567</p>

<h3>Working Hours</h3>
<p>Saturday - Thursday: 9:00 AM - 6:00 PM<br>
Friday: Closed</p>

<h3>Customer Service</h3>
<p>Our customer service team is available to answer your inquiries and assist you with:</p>
<ul>
    <li>Order tracking</li>
    <li>Product inquiries</li>
    <li>Returns and exchanges</li>
    <li>Technical issues</li>
    <li>Any other inquiries</li>
</ul>',
                'meta_description_ar' => 'تواصل مع فريق ركاز للحصول على الدعم والمساعدة في أي وقت',
                'meta_description_en' => 'Contact Rakaz team for support and assistance anytime',
                'meta_keywords_ar' => 'تواصل معنا, خدمة العملاء, دعم فني',
                'meta_keywords_en' => 'contact us, customer service, technical support',
                'status' => 'active',
                'order' => 3,
            ],
            [
                'slug' => 'home',
                'title_ar' => 'الصفحة الرئيسية',
                'title_en' => 'Home Page',
                'content_ar' => '<h1>مرحباً بكم في ركاز</h1>
<p>منصتك الأولى للتسوق الإلكتروني في المملكة العربية السعودية</p>

<h2>لماذا تختار ركاز؟</h2>
<ul>
    <li>منتجات أصلية 100%</li>
    <li>أسعار تنافسية</li>
    <li>توصيل سريع لجميع أنحاء المملكة</li>
    <li>خدمة عملاء متميزة</li>
    <li>دفع آمن ومضمون</li>
    <li>إرجاع واستبدال مجاني</li>
</ul>',
                'content_en' => '<h1>Welcome to Rakaz</h1>
<p>Your premier e-commerce platform in Saudi Arabia</p>

<h2>Why Choose Rakaz?</h2>
<ul>
    <li>100% Authentic Products</li>
    <li>Competitive Prices</li>
    <li>Fast Delivery Across the Kingdom</li>
    <li>Outstanding Customer Service</li>
    <li>Safe and Secure Payment</li>
    <li>Free Returns and Exchanges</li>
</ul>',
                'meta_description_ar' => 'تسوق الآن من ركاز - أفضل منصة للتسوق الإلكتروني في السعودية',
                'meta_description_en' => 'Shop now from Rakaz - The best e-commerce platform in Saudi Arabia',
                'meta_keywords_ar' => 'تسوق اونلاين, منتجات السعودية, ركاز',
                'meta_keywords_en' => 'online shopping, saudi products, rakaz',
                'status' => 'active',
                'order' => 0,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        $this->command->info('تم إضافة الصفحات الافتراضية بنجاح!');
        $this->command->info('Pages created successfully!');
    }
}
