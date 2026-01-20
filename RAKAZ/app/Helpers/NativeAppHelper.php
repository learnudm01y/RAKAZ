<?php

namespace App\Helpers;

/**
 * Native App Helper
 *
 * Helper class to detect native iOS and Android apps using Capacitor
 * and provide platform-specific functionality.
 *
 * Handshake Keys:
 * - iOS: RakazApp-Capacitor-iOS
 * - Android: RakazApp-Capacitor-Android
 */
class NativeAppHelper
{
    /**
     * Handshake key for iOS app
     */
    const IOS_HANDSHAKE_KEY = 'RakazApp-Capacitor-iOS';

    /**
     * Handshake key for Android app
     */
    const ANDROID_HANDSHAKE_KEY = 'RakazApp-Capacitor-Android';

    /**
     * Deep link scheme for the app
     */
    const DEEP_LINK_SCHEME = 'rakaz-app';

    /**
     * Check if the request is from a native app (iOS or Android)
     *
     * @return bool
     */
    public static function isNativeApp(): bool
    {
        return self::isIOSApp() || self::isAndroidApp();
    }

    /**
     * Check if the request is from an iOS app
     *
     * @return bool
     */
    public static function isIOSApp(): bool
    {
        $userAgent = request()->header('User-Agent', '');
        return str_contains($userAgent, self::IOS_HANDSHAKE_KEY);
    }

    /**
     * Check if the request is from an Android app
     *
     * @return bool
     */
    public static function isAndroidApp(): bool
    {
        $userAgent = request()->header('User-Agent', '');
        return str_contains($userAgent, self::ANDROID_HANDSHAKE_KEY);
    }

    /**
     * Get the current platform
     *
     * @return string 'ios' | 'android' | 'web'
     */
    public static function getPlatform(): string
    {
        if (self::isIOSApp()) {
            return 'ios';
        }

        if (self::isAndroidApp()) {
            return 'android';
        }

        return 'web';
    }

    /**
     * Get the appropriate payment callback URL based on platform
     *
     * @return string
     */
    public static function getPaymentCallbackUrl(): string
    {
        $baseUrl = config('app.url');

        // For native apps, we return a URL that will trigger the deep link handler
        if (self::isNativeApp()) {
            return $baseUrl . '/payment/callback?native=1&platform=' . self::getPlatform();
        }

        return $baseUrl . '/payment/callback';
    }

    /**
     * Generate a deep link URL for the native app
     *
     * @param string $path The path within the app (e.g., 'order/success')
     * @param array $params Query parameters to include
     * @return string
     */
    public static function getDeepLinkUrl(string $path, array $params = []): string
    {
        $deepLink = self::DEEP_LINK_SCHEME . '://' . $path;

        if (!empty($params)) {
            $deepLink .= '?' . http_build_query($params);
        }

        return $deepLink;
    }

    /**
     * Get the payment success deep link URL
     *
     * @param string $orderId
     * @param string $orderNumber
     * @return string
     */
    public static function getPaymentSuccessDeepLink(string $orderId, string $orderNumber = ''): string
    {
        return self::getDeepLinkUrl('payment-callback', [
            'status' => 'success',
            'order_id' => $orderId,
            'order_number' => $orderNumber
        ]);
    }

    /**
     * Get the payment failed deep link URL
     *
     * @param string $orderId
     * @param string $reason
     * @return string
     */
    public static function getPaymentFailedDeepLink(string $orderId, string $reason = ''): string
    {
        return self::getDeepLinkUrl('payment-callback', [
            'status' => 'failed',
            'order_id' => $orderId,
            'reason' => $reason
        ]);
    }

    /**
     * Check if Apple Pay should be enabled
     *
     * @return bool
     */
    public static function isApplePayEnabled(): bool
    {
        return self::isIOSApp() && config('myfatoorah.apple_pay.enabled', false);
    }

    /**
     * Check if Google Pay should be enabled
     *
     * @return bool
     */
    public static function isGooglePayEnabled(): bool
    {
        return self::isAndroidApp() && config('myfatoorah.google_pay.enabled', false);
    }

    /**
     * Get payment methods available for the current platform
     *
     * @return array
     */
    public static function getAvailablePaymentMethods(): array
    {
        $methods = ['card', 'knet', 'benefit'];

        if (self::isApplePayEnabled()) {
            $methods[] = 'apple_pay';
        }

        if (self::isGooglePayEnabled()) {
            $methods[] = 'google_pay';
        }

        return $methods;
    }

    /**
     * Get CSS classes for the body element based on platform
     *
     * @return string
     */
    public static function getBodyClasses(): string
    {
        $classes = [];

        if (self::isNativeApp()) {
            $classes[] = 'rakaz-native-app';
        }

        if (self::isIOSApp()) {
            $classes[] = 'rakaz-ios-app';
        }

        if (self::isAndroidApp()) {
            $classes[] = 'rakaz-android-app';
        }

        return implode(' ', $classes);
    }

    /**
     * Get data attributes for the body element
     *
     * @return array
     */
    public static function getBodyDataAttributes(): array
    {
        return [
            'data-platform' => self::getPlatform(),
            'data-native-app' => self::isNativeApp() ? 'true' : 'false',
        ];
    }

    /**
     * Determine if we should hide app download prompts
     *
     * @return bool
     */
    public static function shouldHideAppDownload(): bool
    {
        return self::isNativeApp();
    }

    /**
     * Get the redirect response for payment callback
     *
     * @param string $status 'success' or 'failed'
     * @param array $data Additional data (order_id, order_number, etc.)
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function getPaymentCallbackRedirect(string $status, array $data = [])
    {
        // For native apps, redirect to deep link
        if (self::isNativeApp()) {
            $deepLink = self::getDeepLinkUrl('payment-callback', array_merge([
                'status' => $status
            ], $data));

            return redirect($deepLink);
        }

        // For web, redirect to appropriate page
        if ($status === 'success') {
            return redirect()->route('order.success', ['id' => $data['order_id'] ?? null]);
        }

        return redirect()->route('checkout')->with('error', $data['message'] ?? 'Payment failed');
    }
}
