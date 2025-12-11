<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رد على رسالتك</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7fafc;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .original-message {
            background: #f8fafc;
            border-left: 4px solid #3182ce;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }

        .original-message-title {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .original-message-content {
            color: #1a202c;
            font-size: 15px;
            line-height: 1.8;
        }

        .reply-content {
            color: #2d3748;
            font-size: 16px;
            line-height: 1.8;
            margin: 20px 0;
            white-space: pre-wrap;
        }

        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .footer-brand {
            color: #1a202c;
            font-weight: 600;
            font-size: 18px;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }

        @media (max-width: 600px) {
            .email-header {
                padding: 30px 20px;
            }

            .email-body {
                padding: 30px 20px;
            }

            .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>رد من فريق {{ config('app.name') }}</h1>
            <p>شكراً لتواصلك معنا</p>
        </div>

        <div class="email-body">
            <div class="greeting">
                مرحباً {{ $messageData['customer_name'] }}،
            </div>

            <div class="reply-content">
                {{ $messageData['reply_message'] }}
            </div>

            <div class="divider"></div>

            <div class="original-message">
                <div class="original-message-title">رسالتك الأصلية</div>
                <div class="original-message-content">
                    <strong>الموضوع:</strong> {{ $messageData['original_subject'] }}<br><br>
                    {{ $messageData['original_message'] }}
                </div>
            </div>
        </div>

        <div class="email-footer">
            <p class="footer-text">هذه الرسالة مرسلة من</p>
            <p class="footer-brand">{{ config('app.name') }}</p>
            <p class="footer-text" style="margin-top: 15px; font-size: 12px;">
                يرجى عدم الرد على هذا البريد الإلكتروني مباشرة
            </p>
        </div>
    </div>
</body>
</html>
