/**
 * Capacitor Payment Handler
 * Handles payment flow in Capacitor apps by opening payment URLs in external browser
 */

(function() {
    'use strict';

    /**
     * Handle Deep Link callback from payment
     * This function should be called when the app receives a deep link
     */
    window.handlePaymentDeepLink = function(url) {
        console.log('Payment Deep Link received:', url);

        try {
            const urlObj = new URL(url);
            const params = new URLSearchParams(urlObj.search);

            const status = params.get('status');
            const orderId = params.get('order_id');
            const orderNumber = params.get('order_number');
            const paymentId = params.get('paymentId');

            if (status === 'success') {
                // Payment successful
                alert(document.documentElement.lang === 'ar'
                    ? `تم الدفع بنجاح! رقم الطلب: ${orderNumber}`
                    : `Payment successful! Order number: ${orderNumber}`);

                // Redirect to order details
                if (orderId) {
                    window.location.href = '/order/' + orderId;
                } else {
                    window.location.href = '/orders';
                }
            } else if (status === 'failed') {
                // Payment failed
                alert(document.documentElement.lang === 'ar'
                    ? 'فشل الدفع. يرجى المحاولة مرة أخرى.'
                    : 'Payment failed. Please try again.');

                window.location.href = '/checkout';
            } else {
                // Unknown status - check via API
                if (orderId) {
                    window.location.href = '/api/order/' + orderId + '/payment-status';
                }
            }
        } catch (error) {
            console.error('Error handling deep link:', error);
        }
    };

    /**
     * Detect if running in Capacitor app
     */
    window.isCapacitorApp = function() {
        // Check for Capacitor global object
        if (typeof window.Capacitor !== 'undefined') {
            return true;
        }

        // Check for capacitor-app class on body
        if (document.body && document.body.classList.contains('capacitor-app')) {
            return true;
        }

        // Check user agent for Capacitor
        const userAgent = navigator.userAgent || '';
        if (userAgent.includes('Capacitor') || 
            userAgent.includes('RakazApp-Capacitor') ||
            userAgent.includes('RakazApp-Capacitor-Android') ||
            userAgent.includes('RakazApp-Android-Capacitor')) {
            return true;
        }

        return false;
    };

    /**
     * Add capacitor-app class to body if detected
     */
    function ensureCapacitorClass() {
        if (window.isCapacitorApp() && document.body && !document.body.classList.contains('capacitor-app')) {
            console.log('Adding capacitor-app class to body');
            document.body.classList.add('capacitor-app');
            
            // Also trigger a custom event for other scripts
            document.dispatchEvent(new CustomEvent('capacitor-detected'));
        }
    }

    // Run on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ensureCapacitorClass);
    } else {
        ensureCapacitorClass();
    }

    /**
     * Open URL in external browser (Safari/Chrome) instead of in-app WebView
     */
    window.openInExternalBrowser = function(url) {
        console.log('Opening in external browser:', url);

        // Check if Capacitor Browser plugin is available
        if (typeof window.Capacitor !== 'undefined' && window.Capacitor.Plugins && window.Capacitor.Plugins.Browser) {
            window.Capacitor.Plugins.Browser.open({ url: url });
            return true;
        }

        // Fallback: try to open in system browser
        if (window.Capacitor && window.Capacitor.isNativePlatform && window.Capacitor.isNativePlatform()) {
            // For iOS
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.openExternal) {
                window.webkit.messageHandlers.openExternal.postMessage(url);
                return true;
            }

            // For Android
            if (window.Android && typeof window.Android.openExternal === 'function') {
                window.Android.openExternal(url);
                return true;
            }
        }

        // Final fallback: open in new window/tab
        window.open(url, '_system');
        return true;
    };

    /**
     * Start polling payment status after opening external browser
     */
    window.pollPaymentStatus = function(orderId, callbackUrl) {
        let attempts = 0;
        const maxAttempts = 60; // Poll for 5 minutes (every 5 seconds)
        const pollInterval = 5000; // 5 seconds

        console.log('Starting payment status polling for order:', orderId);

        const intervalId = setInterval(function() {
            attempts++;

            if (attempts > maxAttempts) {
                clearInterval(intervalId);
                console.log('Payment polling timed out');

                // Show timeout message
                if (confirm(document.documentElement.lang === 'ar'
                    ? 'انتهت مهلة التحقق من الدفع. هل تريد التحقق يدوياً؟'
                    : 'Payment verification timed out. Do you want to check manually?')) {
                    window.location.href = callbackUrl || '/orders';
                }
                return;
            }

            // Check payment status via API
            fetch('/api/order/' + orderId + '/payment-status', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment status check attempt ' + attempts + ':', data);

                if (data.success) {
                    if (data.payment_status === 'paid') {
                        // Payment successful
                        clearInterval(intervalId);
                        console.log('Payment successful! Redirecting...');

                        // Redirect to order details or success page
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.href = '/orders';
                        }
                    } else if (data.payment_status === 'failed') {
                        // Payment failed
                        clearInterval(intervalId);
                        console.log('Payment failed');

                        alert(document.documentElement.lang === 'ar'
                            ? 'فشل الدفع. يرجى المحاولة مرة أخرى.'
                            : 'Payment failed. Please try again.');

                        window.location.href = '/checkout';
                    }
                    // If still 'pending', continue polling
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });

        }, pollInterval);
    };

    /**
     * Handle checkout form submission for Capacitor app
     */
    window.handleCapacitorCheckout = function(formElement) {
        console.log('Handling Capacitor checkout');

        // Get form data
        const formData = new FormData(formElement);

        // Add native app flag
        formData.append('is_native_app', '1');

        // Show loading indicator
        const submitButton = formElement.querySelector('button[type="submit"]');
        const originalButtonText = submitButton ? submitButton.textContent : '';
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = document.documentElement.lang === 'ar'
                ? 'جاري المعالجة...'
                : 'Processing...';
        }

        // Send AJAX request to get payment URL
        fetch('/checkout/pay/ajax', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-Native-App': 'rakaz-capacitor',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment response:', data);

            if (data.success && data.payment_url) {
                // Open payment URL in external browser
                openInExternalBrowser(data.payment_url);

                // Start polling for payment status
                pollPaymentStatus(data.order_id);

                // Show message to user
                if (submitButton) {
                    submitButton.textContent = document.documentElement.lang === 'ar'
                        ? 'في انتظار إتمام الدفع...'
                        : 'Waiting for payment...';
                }

                // Optionally show a modal or message
                const message = document.documentElement.lang === 'ar'
                    ? 'تم فتح صفحة الدفع في المتصفح. يرجى إتمام عملية الدفع والعودة للتطبيق.'
                    : 'Payment page opened in browser. Please complete payment and return to the app.';

                if (window.showCapacitorAlert) {
                    window.showCapacitorAlert(message);
                } else {
                    alert(message);
                }

            } else {
                // Error occurred
                const errorMessage = data.message || (document.documentElement.lang === 'ar'
                    ? 'حدث خطأ أثناء معالجة الدفع'
                    : 'Error processing payment');

                alert(errorMessage);

                // Re-enable submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }
            }
        })
        .catch(error => {
            console.error('Checkout error:', error);

            alert(document.documentElement.lang === 'ar'
                ? 'حدث خطأ في الاتصال. يرجى التحقق من الإنترنت والمحاولة مرة أخرى.'
                : 'Connection error. Please check your internet and try again.');

            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        });
    };

    console.log('Capacitor payment handler loaded. Is Capacitor app:', window.isCapacitorApp());
})();
