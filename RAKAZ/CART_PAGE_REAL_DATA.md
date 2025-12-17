# تحديث صفحة السلة لعرض البيانات الحقيقية

## التغييرات المنجزة

### 1. تحديث cart.blade.php
تم استبدال البيانات الافتراضية بالبيانات الحقيقية من قاعدة البيانات.

#### أ) عرض المنتجات الحقيقية
```blade
@foreach($cartItems as $item)
<div class="cart-item" data-cart-id="{{ $item->id }}">
    @php
        $product = $item->product;
        $mainImage = null;
        
        if ($product->main_image) {
            $mainImage = asset('storage/' . $product->main_image);
        } elseif ($product->images && is_array($product->images) && count($product->images) > 0) {
            $mainImage = asset('storage/' . $product->images[0]);
        } else {
            $mainImage = asset('assets/images/placeholder.jpg');
        }
        
        $productName = $product->name;
    @endphp
    
    <img src="{{ $mainImage }}" alt="{{ $productName }}" class="cart-item-image">
    <div class="cart-item-details">
        <div class="cart-item-brand">{{ $product->brand ?? 'ركاز' }}</div>
        <div class="cart-item-name">{{ $productName }}</div>
        <div class="cart-item-specs">
            @if($item->size)
                <span class="cart-item-spec">المقاس: {{ $item->size }}</span>
            @endif
            @if($item->shoe_size)
                <span class="cart-item-spec">مقاس الحذاء: {{ $item->shoe_size }}</span>
            @endif
            @if($item->color)
                <span class="cart-item-spec">اللون: {{ $item->color }}</span>
            @endif
            @if($product->fabric)
                <span class="cart-item-spec">القماش: {{ $product->fabric }}</span>
            @endif
        </div>
        <div class="cart-item-price">{{ number_format($item->price, 0) }} د.إ</div>
    </div>
    <div class="cart-item-actions">
        <button class="remove-item-btn" data-cart-id="{{ $item->id }}" title="إزالة من السلة">
            <!-- SVG icon -->
        </button>
        <div class="quantity-selector">
            <button class="quantity-btn quantity-decrease" data-cart-id="{{ $item->id }}">-</button>
            <span class="quantity-value">{{ $item->quantity }}</span>
            <button class="quantity-btn quantity-increase" data-cart-id="{{ $item->id }}">+</button>
        </div>
    </div>
</div>
@endforeach
```

#### ب) عرض الملخص الحقيقي
```blade
<div class="cart-summary">
    <h3 class="summary-title">ملخص الطلب</h3>
    <div class="summary-row">
        <span>المجموع الفرعي</span>
        <span id="subtotal">{{ number_format($cartTotal, 2) }} د.إ</span>
    </div>
    <div class="summary-row">
        <span>الشحن</span>
        <span>مجاني</span>
    </div>
    <div class="summary-row">
        <span>الضريبة (5%)</span>
        <span id="tax">{{ number_format($cartTotal * 0.05, 2) }} د.إ</span>
    </div>
    <div class="summary-total">
        <span>المجموع الكلي</span>
        <span id="total">{{ number_format($cartTotal * 1.05, 2) }} د.إ</span>
    </div>
    <button class="checkout-btn" onclick="window.location.href='{{ route('checkout.index') }}'">إتمام الطلب</button>
    <a href="{{ route('home') }}" class="continue-shopping">متابعة التسوق</a>
</div>
```

#### ج) حالة السلة الفارغة
```blade
@if($cartItems && count($cartItems) > 0)
    <!-- عرض المنتجات -->
@else
    <!-- Empty Cart -->
    <div style="text-align: center; padding: 80px 20px;">
        <svg width="120" height="120">...</svg>
        <h2>سلة التسوق فارغة</h2>
        <p>لم تقم بإضافة أي منتجات إلى سلة التسوق بعد</p>
        <a href="{{ route('shop') }}" class="checkout-btn">تصفح المنتجات</a>
    </div>
@endif
```

### 2. إضافة JavaScript للوظائف التفاعلية

#### أ) حذف منتج من السلة
```javascript
document.querySelectorAll('.remove-item-btn').forEach(button => {
    button.addEventListener('click', async function() {
        const cartId = this.dataset.cartId;
        
        const result = await Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم إزالة المنتج من سلة التسوق',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Remove from DOM
                const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                cartItem.remove();
                updateCartSummary(data.cartTotal);
                updateCartCount();
            }
        }
    });
});
```

#### ب) تحديث الكمية
```javascript
// Increase
document.querySelectorAll('.quantity-increase').forEach(button => {
    button.addEventListener('click', async function() {
        const cartId = this.dataset.cartId;
        const quantitySpan = this.previousElementSibling;
        const currentQuantity = parseInt(quantitySpan.textContent);
        
        await updateQuantity(cartId, currentQuantity + 1, quantitySpan);
    });
});

// Decrease
document.querySelectorAll('.quantity-decrease').forEach(button => {
    button.addEventListener('click', async function() {
        const cartId = this.dataset.cartId;
        const quantitySpan = this.nextElementSibling;
        const currentQuantity = parseInt(quantitySpan.textContent);
        
        if (currentQuantity > 1) {
            await updateQuantity(cartId, currentQuantity - 1, quantitySpan);
        } else {
            // If quantity is 1, trigger remove
            document.querySelector(`.remove-item-btn[data-cart-id="${cartId}"]`).click();
        }
    });
});

async function updateQuantity(cartId, newQuantity, quantitySpan) {
    const response = await fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newQuantity })
    });

    const data = await response.json();

    if (data.success) {
        quantitySpan.textContent = newQuantity;
        updateCartSummary(data.cartTotal);
        updateCartCount();
    }
}
```

#### ج) تحديث الملخص
```javascript
function updateCartSummary(total) {
    const subtotal = parseFloat(total);
    const tax = subtotal * 0.05;
    const grandTotal = subtotal + tax;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' د.إ';
    document.getElementById('tax').textContent = tax.toFixed(2) + ' د.إ';
    document.getElementById('total').textContent = grandTotal.toFixed(2) + ' د.إ';
}

function updateCartCount() {
    if (typeof window.updateCartCount === 'function') {
        window.updateCartCount();
    }
    
    // Also update sidebar
    if (window.cartSidebarInstance) {
        window.cartSidebarInstance.loadCartFromServer();
    }
}
```

### 3. تحديث الروابط
```blade
<!-- Old (hardcoded HTML files) -->
<button onclick="window.location.href='wishlist.html'">

<!-- New (Laravel routes) -->
<a href="{{ route('wishlist') }}" class="wishlist-action-btn">
<a href="{{ route('orders.index') }}" class="wishlist-action-btn">
<a href="{{ route('cart.index') }}" class="wishlist-action-btn">
```

## البيانات المعروضة

### من قاعدة البيانات (carts table):
- `id` - معرف السلة
- `product_id` - معرف المنتج
- `quantity` - الكمية
- `price` - السعر
- `size` - المقاس (إذا وجد)
- `shoe_size` - مقاس الحذاء (إذا وجد)
- `color` - اللون (إذا وجد)

### من علاقة Product:
- `name` - اسم المنتج
- `brand` - الماركة
- `main_image` - الصورة الرئيسية
- `images` - معرض الصور
- `fabric` - نوع القماش (إذا وجد)

## التكامل مع النظام

### CartController::index()
```php
public function index()
{
    $identifier = $this->getIdentifier();
    $cartItems = Cart::getCartItems($identifier['user_id'], $identifier['session_id']);
    $cartTotal = Cart::getCartTotal($identifier['user_id'], $identifier['session_id']);

    return view('frontend.cart', compact('cartItems', 'cartTotal'));
}
```

### Cart Model Methods
```php
// جلب عناصر السلة
public static function getCartItems($userId = null, $sessionId = null)
{
    $query = self::with('product');
    
    if ($userId) {
        $query->where('user_id', $userId);
    } else {
        $query->where('session_id', $sessionId);
    }
    
    return $query->get();
}

// حساب المجموع
public static function getCartTotal($userId = null, $sessionId = null)
{
    $items = self::getCartItems($userId, $sessionId);
    return $items->sum('subtotal');
}

// عدد المنتجات
public static function getCartCount($userId = null, $sessionId = null)
{
    $query = self::query();
    
    if ($userId) {
        $query->where('user_id', $userId);
    } else {
        $query->where('session_id', $sessionId);
    }
    
    return $query->sum('quantity');
}
```

## الميزات

### ✅ عرض البيانات الحقيقية
- جميع المنتجات من قاعدة البيانات
- الصور من storage (مع fallback للصور الافتراضية)
- الأسعار والكميات الحقيقية
- المقاسات والألوان المختارة

### ✅ التفاعل الكامل
- حذف منتج (مع تأكيد SweetAlert)
- زيادة/إنقاص الكمية
- تحديث الملخص تلقائياً
- تحديث badge الهيدر
- تحديث sidebar السلة

### ✅ UX محسّنة
- Animation عند الحذف
- رسائل تأكيد جميلة
- حالة السلة الفارغة
- روابط صحيحة (Laravel routes)
- Responsive design

### ✅ الأمان
- CSRF token في كل طلب
- Validation في Backend
- التحقق من الملكية (user_id/session_id)

## اختبار الوظائف

### 1. إضافة منتج للسلة
```
1. اذهب إلى /shop
2. افتح modal منتج
3. اختر المقاس
4. اضغط "إضافة إلى السلة"
```

### 2. عرض السلة
```
1. اذهب إلى http://127.0.0.1:8000/cart
2. يجب أن ترى المنتجات التي أضفتها
```

### 3. تعديل الكمية
```
1. اضغط + لزيادة الكمية
2. اضغط - لإنقاص الكمية
3. إذا وصلت لـ 0، سيتم الحذف
```

### 4. حذف منتج
```
1. اضغط أيقونة الحذف (سلة المهملات)
2. أكد الحذف
3. المنتج يختفي مع animation
```

## الملفات المعدلة

1. `resources/views/frontend/cart.blade.php` - تحديث كامل
   - استبدال HTML الثابت بـ Blade loops
   - إضافة JavaScript للتفاعل
   - تحديث الروابط

2. `app/Http/Controllers/CartController.php` - جاهز (لا يحتاج تعديل)
3. `app/Models/Cart.php` - جاهز (لا يحتاج تعديل)

## Next Steps (اختياري)

- [ ] إضافة loading states أثناء العمليات
- [ ] إضافة animation للكمية
- [ ] حفظ آخر سلة مشاهدة
- [ ] إضافة "Recently Viewed" في sidebar
- [ ] نظام كوبونات الخصم الحقيقي
- [ ] حساب الشحن بناءً على المنطقة
