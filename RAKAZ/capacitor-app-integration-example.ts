/**
 * Capacitor App Integration Example
 * Add this to your Capacitor app's main file (App.tsx, main.ts, or similar)
 */

import { App } from '@capacitor/app';
import { Browser } from '@capacitor/browser';

// ========================================
// 1. Deep Link Handler
// ========================================

/**
 * Initialize Deep Link listener for payment callbacks
 */
export function initializePaymentDeepLinks() {
    App.addListener('appUrlOpen', (event) => {
        const url = event.url;
        console.log('App opened with URL:', url);

        // Check if this is a payment callback
        if (url.startsWith('rakaz-app://payment-callback')) {
            handlePaymentCallback(url);
        }
    });
}

/**
 * Handle payment callback from MyFatoorah
 */
function handlePaymentCallback(url: string) {
    try {
        const urlObj = new URL(url);
        const params = new URLSearchParams(urlObj.search);

        const status = params.get('status');
        const orderId = params.get('order_id');
        const orderNumber = params.get('order_number');
        const paymentId = params.get('paymentId');

        console.log('Payment callback received:', {
            status,
            orderId,
            orderNumber,
            paymentId
        });

        if (status === 'success') {
            // Payment successful - show success message
            showSuccessMessage(orderNumber);

            // Navigate to order details
            navigateToOrder(orderId);

        } else if (status === 'failed') {
            // Payment failed - show error message
            showErrorMessage('فشل الدفع. يرجى المحاولة مرة أخرى.');

            // Navigate back to checkout
            navigateToCheckout();

        } else {
            // Unknown status - poll payment status
            if (orderId) {
                pollPaymentStatus(orderId);
            }
        }

    } catch (error) {
        console.error('Error handling payment callback:', error);
        showErrorMessage('حدث خطأ في معالجة نتيجة الدفع');
    }
}

// ========================================
// 2. Navigation Functions
// ========================================

function navigateToOrder(orderId: string) {
    // React Router example
    // window.location.href = `/order/${orderId}`;

    // Or use your router
    // router.push(`/order/${orderId}`);

    // Capacitor WebView navigation
    window.location.href = `/order/${orderId}`;
}

function navigateToCheckout() {
    window.location.href = '/checkout';
}

// ========================================
// 3. UI Feedback Functions
// ========================================

function showSuccessMessage(orderNumber: string) {
    // Use your app's notification system
    // Example with native alert
    const message = `تم الدفع بنجاح! رقم الطلب: ${orderNumber}`;

    // Native alert
    alert(message);

    // Or use Capacitor Dialog
    // import { Dialog } from '@capacitor/dialog';
    // Dialog.alert({
    //     title: 'نجح الدفع',
    //     message: message
    // });

    // Or use your UI library (Ionic, etc.)
    // present toast, modal, etc.
}

function showErrorMessage(message: string) {
    alert(message);
}

// ========================================
// 4. Payment Status Polling (Fallback)
// ========================================

async function pollPaymentStatus(orderId: string) {
    let attempts = 0;
    const maxAttempts = 60; // 5 minutes

    const interval = setInterval(async () => {
        attempts++;

        if (attempts > maxAttempts) {
            clearInterval(interval);
            showErrorMessage('انتهت مهلة التحقق من الدفع');
            return;
        }

        try {
            const response = await fetch(`/api/order/${orderId}/payment-status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Native-App': 'rakaz-capacitor'
                }
            });

            const data = await response.json();

            if (data.success) {
                if (data.payment_status === 'paid') {
                    clearInterval(interval);
                    showSuccessMessage(data.order_number);
                    navigateToOrder(orderId);
                } else if (data.payment_status === 'failed') {
                    clearInterval(interval);
                    showErrorMessage('فشل الدفع');
                    navigateToCheckout();
                }
            }

        } catch (error) {
            console.error('Error polling payment status:', error);
        }

    }, 5000); // Every 5 seconds
}

// ========================================
// 5. Custom WebView User-Agent (Optional)
// ========================================

/**
 * Set custom User-Agent for WebView
 * Add this to your Capacitor config or use a plugin
 */
export function setCustomUserAgent() {
    // Using capacitor-plugin-user-agent or similar
    // UserAgent.set({ userAgent: 'RakazApp-Capacitor/1.0.0' });

    // Or modify in capacitor.config.ts:
    // plugins: {
    //     CapacitorHttp: {
    //         headers: {
    //             'User-Agent': 'RakazApp-Capacitor/1.0.0'
    //         }
    //     }
    // }
}

// ========================================
// 6. Initialize on App Start
// ========================================

/**
 * Call this when your app starts
 */
export function initializeApp() {
    console.log('Initializing Rakaz app...');

    // Initialize Deep Links
    initializePaymentDeepLinks();

    // Set custom User-Agent (optional)
    // setCustomUserAgent();

    // Add global flag for web detection
    (window as any).isRakazNativeApp = () => true;

    console.log('App initialized successfully');
}

// ========================================
// 7. Example: React App.tsx
// ========================================

/*
import React, { useEffect } from 'react';
import { IonApp, setupIonicReact } from '@ionic/react';
import { initializeApp } from './utils/capacitor-integration';

setupIonicReact();

const App: React.FC = () => {
    useEffect(() => {
        // Initialize Capacitor integration
        initializeApp();
    }, []);

    return (
        <IonApp>
            {/* Your app content *\/}
        </IonApp>
    );
};

export default App;
*/

// ========================================
// 8. Example: Vue main.ts
// ========================================

/*
import { createApp } from 'vue';
import App from './App.vue';
import { initializeApp } from './utils/capacitor-integration';

const app = createApp(App);

// Initialize Capacitor
initializeApp();

app.mount('#app');
*/

// ========================================
// 9. Testing Deep Links
// ========================================

/**
 * Test deep link handling in development
 */
export function testDeepLink() {
    // Simulate successful payment
    const testUrl = 'rakaz-app://payment-callback?paymentId=test123&status=success&order_id=456&order_number=ORD-001';
    handlePaymentCallback(testUrl);

    // Simulate failed payment
    // const testUrl = 'rakaz-app://payment-callback?paymentId=test123&status=failed&order_id=456';
    // handlePaymentCallback(testUrl);
}

// For testing in browser console:
// (window as any).testDeepLink = testDeepLink;

// ========================================
// Export all functions
// ========================================

export {
    handlePaymentCallback,
    pollPaymentStatus,
    navigateToOrder,
    navigateToCheckout,
    showSuccessMessage,
    showErrorMessage
};
