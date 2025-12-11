<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$page = App\Models\PrivacyPolicyPage::first();

$contentEn = <<<'HTML'
<div class="privacy-section">
    <h2>Introduction</h2>
    <p>At <strong>Rakaz</strong>, we are committed to protecting your privacy and respecting your personal data. This privacy policy explains how we collect, use, protect, and share your personal information when you use our website and services.</p>
    <p>By using our website, you agree to the collection and use of information in accordance with this policy.</p>
</div>

<div class="privacy-section">
    <h2>1. Information We Collect</h2>

    <h3>1.1 Personal Information</h3>
    <p>We may collect the following information when you use our website:</p>
    <ul>
        <li>Full name</li>
        <li>Email address</li>
        <li>Phone number</li>
        <li>Delivery address</li>
        <li>Payment information (processed securely through trusted payment gateways)</li>
        <li>Shopping preferences and order history</li>
    </ul>

    <h3>1.2 Technical Information</h3>
    <p>We also automatically collect technical information when you visit our website:</p>
    <ul>
        <li>IP address</li>
        <li>Browser type and version</li>
        <li>Operating system</li>
        <li>Pages you visit on the website</li>
        <li>Time and date of your visit</li>
        <li>Referring source</li>
    </ul>
</div>

<div class="privacy-section">
    <h2>2. How We Use Your Information</h2>
    <p>We use the information we collect for the following purposes:</p>
    <ul>
        <li><strong>Order Processing:</strong> To process your orders and deliver products to you</li>
        <li><strong>Communication:</strong> To communicate with you about your orders and respond to your inquiries</li>
        <li><strong>Service Improvement:</strong> To improve our website and services based on your feedback</li>
        <li><strong>Marketing:</strong> To send promotional offers and advertisements (you can unsubscribe at any time)</li>
        <li><strong>Security:</strong> To protect our website and prevent fraud</li>
        <li><strong>Legal Compliance:</strong> To comply with legal obligations</li>
    </ul>
</div>

<div class="privacy-section">
    <h2>3. Cookies</h2>
    <p>We use cookies and similar technologies to enhance your experience on our website. Cookies are small files stored on your device when you visit the website.</p>

    <h3>3.1 Types of Cookies We Use</h3>
    <ul>
        <li><strong>Essential Cookies:</strong> Required for the website to function properly</li>
        <li><strong>Performance Cookies:</strong> Help us understand how visitors interact with the website</li>
        <li><strong>Functionality Cookies:</strong> Remember your preferences and choices</li>
        <li><strong>Marketing Cookies:</strong> Used to display relevant advertisements</li>
    </ul>

    <h3>3.2 Managing Cookies</h3>
    <p>You can control cookies through your browser settings. However, disabling some cookies may affect the website's functionality.</p>
</div>

<div class="privacy-section">
    <h2>4. Sharing Your Information</h2>
    <p>We do not sell or rent your personal information to third parties. We may share your information only in the following cases:</p>
    <ul>
        <li><strong>Service Providers:</strong> Shipping companies, payment processors, and technical service providers</li>
        <li><strong>Legal Compliance:</strong> When required by law</li>
        <li><strong>Rights Protection:</strong> To protect our rights, property, and the safety of our customers</li>
    </ul>
</div>

<div class="privacy-section">
    <h2>5. Data Security</h2>
    <p>We take appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
    <ul>
        <li>SSL encryption to protect data during transmission</li>
        <li>Secure storage of data on protected servers</li>
        <li>Limited access to personal data</li>
        <li>Regular security reviews</li>
    </ul>
</div>

<div class="privacy-section">
    <h2>6. Your Rights</h2>
    <p>You have the following rights regarding your personal information:</p>
    <ul>
        <li><strong>Access:</strong> The right to request a copy of your personal data</li>
        <li><strong>Correction:</strong> The right to correct inaccurate information</li>
        <li><strong>Deletion:</strong> The right to request deletion of your data in certain circumstances</li>
        <li><strong>Objection:</strong> The right to object to the processing of your data</li>
        <li><strong>Data Portability:</strong> The right to obtain a copy of your data in a portable format</li>
        <li><strong>Withdrawal of Consent:</strong> The right to withdraw your consent at any time</li>
    </ul>
    <p>To exercise any of these rights, please contact us using the contact information below.</p>
</div>

<div class="contact-info">
    <h3>Contact Us</h3>
    <p>If you have any questions or inquiries about this privacy policy, please contact us:</p>
    <p><strong>Email:</strong> <a href="mailto:privacy@rakaz.com">privacy@rakaz.com</a></p>
    <p><strong>Phone:</strong> <a href="tel:+971-xxx-xxxx">+971-xxx-xxxx</a></p>
    <p><strong>Address:</strong> Dubai, United Arab Emirates</p>
</div>
HTML;

$page->content_en = $contentEn;
$page->hero_title_en = 'Privacy Policy and Cookies';
$page->hero_subtitle_en = 'Last updated: December 3, 2025';
$page->save();

echo "Privacy policy English content updated successfully!\n";
echo "Content EN length: " . strlen($page->content_en) . " characters\n";
