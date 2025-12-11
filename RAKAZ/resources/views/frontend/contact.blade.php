@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/assets/css/contact.css">
    <style>
        .additional-info-section {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .additional-info-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1a1a1a;
            text-align: center;
        }

        .additional-info-section .info-content {
            line-height: 1.8;
            color: #444;
        }

        .additional-info-section .info-content p {
            margin-bottom: 1rem;
        }

        .additional-info-section .info-content ul {
            margin: 1rem 0;
            padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 2rem;
        }

        .additional-info-section .info-content li {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <!-- Contact Page Content -->
    <main class="contact-page">
        <div class="page-header">
            @if(app()->getLocale() == 'ar')
                <h1>{{ $page->hero_title_ar ?? 'اتصل بنا' }}</h1>
                <p>{{ $page->hero_subtitle_ar ?? 'نحن هنا لخدمتك. تواصل معنا عبر أي من الطرق التالية' }}</p>
            @else
                <h1>{{ $page->hero_title_en ?? 'Contact Us' }}</h1>
                <p>{{ $page->hero_subtitle_en ?? 'We are here to serve you. Contact us through any of the following methods' }}</p>
            @endif
        </div>

        <div class="contact-container">
            <!-- Contact Info -->
            <div class="contact-info-section">
                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <h3>{{ app()->getLocale() == 'ar' ? 'الهاتف' : 'Phone' }}</h3>
                    <p><a href="tel:{{ $page->phone ?? '800717171' }}">{{ $page->phone ?? '800 717171' }}</a></p>
                    @if(app()->getLocale() == 'ar')
                        <p class="info-note">خدمة العملاء متاحة من 9 صباحاً - 10 مساءً</p>
                    @else
                        <p class="info-note">Customer service available from 9 AM - 10 PM</p>
                    @endif
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3>{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h3>
                    <p><a href="mailto:{{ $page->email ?? 'info@kandorahouse.com' }}">{{ $page->email ?? 'info@kandorahouse.com' }}</a></p>
                    @if(app()->getLocale() == 'ar')
                        <p class="info-note">سنرد عليك خلال 24 ساعة</p>
                    @else
                        <p class="info-note">We will reply within 24 hours</p>
                    @endif
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3>{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</h3>
                    @if(app()->getLocale() == 'ar')
                        <p>{!! nl2br(e($page->address_ar ?? 'دبي مول، الطابق الأرضي<br>دبي، الإمارات العربية المتحدة')) !!}</p>
                    @else
                        <p>{!! nl2br(e($page->address_en ?? 'Dubai Mall, Ground Floor<br>Dubai, United Arab Emirates')) !!}</p>
                    @endif
                </div>

                <div class="info-card">
                    <div class="info-icon whatsapp-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <h3>{{ app()->getLocale() == 'ar' ? 'واتساب' : 'WhatsApp' }}</h3>
                    <p><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $page->whatsapp ?? '971553007879') }}">{{ $page->whatsapp ?? '+971 55 300 7879' }}</a></p>
                    @if(app()->getLocale() == 'ar')
                        <p class="info-note">تواصل معنا مباشرة على واتساب</p>
                    @else
                        <p class="info-note">Contact us directly on WhatsApp</p>
                    @endif
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2>{{ app()->getLocale() == 'ar' ? 'أرسل لنا رسالة' : 'Send us a message' }}</h2>
                <form class="contact-form" id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">{{ app()->getLocale() == 'ar' ? 'الاسم الأول *' : 'First Name *' }}</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">{{ app()->getLocale() == 'ar' ? 'الاسم الأخير *' : 'Last Name *' }}</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني *' : 'Email *' }}</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="subject">{{ app()->getLocale() == 'ar' ? 'الموضوع *' : 'Subject *' }}</label>
                        <select id="subject" name="subject" required>
                            @if(app()->getLocale() == 'ar')
                                <option value="">اختر الموضوع</option>
                                <option value="order">استفسار عن طلب</option>
                                <option value="product">استفسار عن منتج</option>
                                <option value="complaint">شكوى</option>
                                <option value="suggestion">اقتراح</option>
                                <option value="other">أخرى</option>
                            @else
                                <option value="">Choose Subject</option>
                                <option value="order">Order Inquiry</option>
                                <option value="product">Product Inquiry</option>
                                <option value="complaint">Complaint</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="other">Other</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">{{ app()->getLocale() == 'ar' ? 'الرسالة *' : 'Message *' }}</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">{{ app()->getLocale() == 'ar' ? 'إرسال الرسالة' : 'Send Message' }}</button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <div class="map-section">
            <h2>{{ app()->getLocale() == 'ar' ? 'موقعنا على الخريطة' : 'Find Us on Map' }}</h2>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.168778814193!2d55.27886431501204!3d25.197196983897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f682829c85bf9%3A0x93d2104d0d0c2c3a!2sDubai%20Mall!5e0!3m2!1sen!2sae!4v1234567890123!5m2!1sen!2sae"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <!-- Additional Information Section -->
        @if(($page->working_hours_ar && app()->getLocale() == 'ar') || ($page->working_hours_en && app()->getLocale() == 'en'))
        <div class="additional-info-section">
            <h2>{{ app()->getLocale() == 'ar' ? 'أوقات العمل' : 'Working Hours' }}</h2>
            <div class="info-content">
                @if(app()->getLocale() == 'ar')
                    {!! $page->working_hours_ar !!}
                @else
                    {!! $page->working_hours_en !!}
                @endif
            </div>
        </div>
        @endif

        @if(($page->additional_info_ar && app()->getLocale() == 'ar') || ($page->additional_info_en && app()->getLocale() == 'en'))
        <div class="additional-info-section">
            <h2>{{ app()->getLocale() == 'ar' ? 'معلومات إضافية' : 'Additional Information' }}</h2>
            <div class="info-content">
                @if(app()->getLocale() == 'ar')
                    {!! $page->additional_info_ar !!}
                @else
                    {!! $page->additional_info_en !!}
                @endif
            </div>
        </div>
        @endif
    </main>
@endsection

@push('scripts')
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Collect form data
            const formData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value,
                _token: '{{ csrf_token() }}'
            };

            // Send AJAX request
            fetch('/contact/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    @if(app()->getLocale() == 'ar')
                        Swal.fire({
                            title: 'تم إرسال رسالتك!',
                            text: 'شكراً لتواصلك معنا. سنرد عليك في أقرب وقت ممكن.',
                            icon: 'success',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#1a1a1a'
                        }).then(() => {
                            document.getElementById('contactForm').reset();
                        });
                    @else
                        Swal.fire({
                            title: 'Message Sent!',
                            text: 'Thank you for contacting us. We will reply as soon as possible.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#1a1a1a'
                        }).then(() => {
                            document.getElementById('contactForm').reset();
                        });
                    @endif
                }
            })
            .catch(error => {
                @if(app()->getLocale() == 'ar')
                    Swal.fire({
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.',
                        icon: 'error',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#d33'
                    });
                @else
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while sending the message. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                @endif
            });
        });
    </script>
@endpush
