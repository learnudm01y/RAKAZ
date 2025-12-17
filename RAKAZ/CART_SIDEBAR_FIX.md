# Cart Sidebar Fix - Summary

## المشكلة
- المنتجات تضاف للسلة بنجاح في الـ database
- الـ badge في الهيدر يتحدث بشكل صحيح
- **لكن** عند فتح الـ sidebar، لا تظهر المنتجات

## السبب
cart-sidebar.js كان يستخدم localStorage (client-side storage) بدلاً من Laravel backend API

## الحل المنجز

### 1. إضافة API Endpoint
**File:** `routes/web.php`
```php
Route::get('/api/cart', [CartController::class, 'apiIndex'])->name('cart.api');
```

### 2. إضافة API Method في Controller
**File:** `app/Http/Controllers/CartController.php`
```php
public function apiIndex()
{
    $identifier = $this->getIdentifier();
    
    $cartItems = Cart::with('product')
        ->where($identifier['user_id'] ? 'user_id' : 'session_id', 
                $identifier['user_id'] ?: $identifier['session_id'])
        ->get();

    $items = $cartItems->map(function($item) {
        $product = $item->product;
        $mainImage = null;
        
        // Get main image
        if ($product->main_image) {
            $mainImage = asset('storage/' . $product->main_image);
        } elseif ($product->images && is_array($product->images) && count($product->images) > 0) {
            $mainImage = asset('storage/' . $product->images[0]);
        }
        
        return [
            'id' => $item->id,
            'image' => $mainImage,
            'brand' => $product->brand ?? '',
            'name' => app()->getLocale() == 'ar' ? $product->name_ar : $product->name_en,
            'price' => number_format($item->price, 0) . ' د.إ',
            'size' => $item->size ?? $item->shoe_size ?? '',
            'quantity' => $item->quantity
        ];
    });

    return response()->json([
        'items' => $items
    ]);
}
```

### 3. تحديث cart-sidebar.js

#### أ) إضافة loadCartFromServer()
```javascript
async loadCartFromServer() {
    try {
        const response = await fetch('/api/cart', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            this.cart = data.items || [];
            this.updateCartDisplay();
            console.log('Cart loaded:', this.cart.length, 'items');
        }
    } catch (error) {
        console.error('Error loading cart:', error);
    }
}
```

#### ب) تحديث removeFromCart()
```javascript
async removeFromCart(productId) {
    try {
        const response = await fetch(`/cart/${productId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                await this.loadCartFromServer();
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
                this.showNotification('تم الحذف', data.message);
            }
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
    }
}
```

#### ج) تحديث updateQuantity()
```javascript
async updateQuantity(productId, change) {
    const item = this.cart.find(item => item.id === productId);
    if (!item) return;
    
    const newQuantity = item.quantity + change;
    
    if (newQuantity <= 0) {
        await this.removeFromCart(productId);
        return;
    }

    try {
        const response = await fetch(`/cart/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                await this.loadCartFromServer();
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            }
        }
    } catch (error) {
        console.error('Error updating quantity:', error);
    }
}
```

#### د) تحديث clearCart()
```javascript
async clearCart() {
    try {
        const response = await fetch('/cart', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                await this.loadCartFromServer();
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
                this.showNotification('تم التفريغ', data.message);
            }
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
    }
}
```

#### هـ) تصدير Instance
```javascript
// Initialize Cart Sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.cartSidebarInstance = new CartSidebar();
});
```

### 4. تحديث Add to Cart في Views

#### أ) product-details.blade.php
```javascript
.then(data => {
    if (data.success) {
        // Update cart count
        if (typeof updateCartCount === 'function') {
            updateCartCount();
        }
        const cartBadge = document.getElementById('cartBadge');
        if (cartBadge && data.cartCount !== undefined) {
            cartBadge.textContent = data.cartCount;
        }
        
        // Update cart sidebar ⭐ NEW
        if (window.cartSidebarInstance && typeof window.cartSidebarInstance.loadCartFromServer === 'function') {
            window.cartSidebarInstance.loadCartFromServer();
        }
        
        // Show success message
        Swal.fire({...});
    }
})
```

#### ب) shop.blade.php (نفس التعديل)

## كيفية الاختبار

### 1. من المتصفح
1. افتح صفحة منتج أو صفحة المتجر
2. أضف منتج للسلة
3. افتح الـ sidebar (اضغط على أيقونة السلة في الهيدر)
4. يجب أن ترى المنتجات المضافة

### 2. من Console
```javascript
// اختبر API مباشرة
fetch('/api/cart', {
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
})
.then(r => r.json())
.then(d => console.log('Cart items:', d.items));

// أعد تحميل السلة
window.cartSidebarInstance.loadCartFromServer();
```

### 3. من ملف HTML للاختبار
افتح: `http://localhost/test-cart-api.html`

## Routes المستخدمة

```
GET    /api/cart          → CartController@apiIndex     (جلب محتويات السلة)
POST   /cart/add          → CartController@add          (إضافة منتج)
PUT    /cart/{id}         → CartController@update       (تحديث الكمية)
DELETE /cart/{id}         → CartController@remove       (حذف منتج)
DELETE /cart              → CartController@clear        (تفريغ السلة)
GET    /cart/count        → CartController@count        (عدد المنتجات)
```

## الميزات الجديدة

✅ السلة الآن متزامنة بين الـ sidebar والـ database
✅ يتم تحديث السلة تلقائياً بعد كل إضافة
✅ زر الحذف يعمل عبر API
✅ تحديث الكمية (+/-) يعمل عبر API
✅ تفريغ السلة يعمل عبر API
✅ دعم المستخدمين المسجلين والضيوف (session-based)
✅ الصور والأسعار تأتي من Product model
✅ دعم اللغة العربية/الإنجليزية للأسماء

## الأخطاء المحتملة وحلولها

### 1. السلة لا تزال فارغة
- امسح الـ localStorage: `localStorage.clear()`
- تأكد من أن الـ session_id نفسه في الطلبات
- افحص الـ Network tab في Developer Tools

### 2. خطأ 404 على /api/cart
```bash
php artisan route:list --path=cart
php artisan route:cache
```

### 3. خطأ 500 من API
- افحص `storage/logs/laravel.log`
- تأكد من وجود العلاقة `product()` في Cart model
- تأكد من أن المنتجات موجودة في جدول products

## الملفات المعدلة

1. `routes/web.php` - إضافة API route
2. `app/Http/Controllers/CartController.php` - إضافة apiIndex()
3. `public/assets/js/cart-sidebar.js` - تحويل لـ API-based
4. `resources/views/frontend/product-details.blade.php` - تحديث السلة بعد الإضافة
5. `resources/views/frontend/shop.blade.php` - تحديث السلة بعد الإضافة

## Next Steps (اختياري)

- [ ] إضافة loading spinner عند فتح الـ sidebar
- [ ] إضافة animation لتحديث الكمية
- [ ] حفظ السلة للمستخدمين المسجلين عند تسجيل الدخول
- [ ] دمج سلة الـ session مع سلة المستخدم بعد تسجيل الدخول
- [ ] إضافة debounce لتحديث الكمية (تجنب طلبات كثيرة)
