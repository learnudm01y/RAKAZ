# دليل Routes الكامل - RAKAZ E-commerce

## التاريخ: 16 ديسمبر 2025

---

## ✅ المشكلة المحلولة:

**الخطأ السابق:**
```
Route [orders.index] not defined.
```

**السبب:**
كان route الطلبات معرف باسم `orders` فقط، بينما بعض الملفات تستخدم `orders.index`

**الحل:**
- تم تغيير اسم route من `orders` إلى `orders.index` ليكون متوافقاً مع باقي التطبيق
- تم تحديث جميع الملفات التي تستخدم `route('orders')` لتصبح `route('orders.index')`

---

## 📋 قائمة Routes الرئيسية

### 🛍️ Frontend Routes

#### 1. الصفحات الرئيسية
```php
GET  /                  → home               → FrontendController@index
GET  /about             → about              → FrontendController@about
GET  /shop              → shop               → FrontendController@shop
GET  /contact           → contact            → FrontendController@contact
POST /contact/submit    → contact.submit     → FrontendController@submitContact
GET  /privacy-policy    → privacy.policy     → FrontendController@privacyPolicy
```

#### 2. المنتجات
```php
GET  /category/{slug}   → category.show      → FrontendController@category
GET  /product/{slug}    → product.details    → FrontendController@productDetails
```

#### 3. السلة (Cart) 🛒
```php
GET     /cart           → cart               → CartController@index (يتطلب تسجيل دخول)
GET     /cart/index     → cart.index         → CartController@index
POST    /cart/add       → cart.add           → CartController@add
PUT     /cart/{id}      → cart.update        → CartController@update
DELETE  /cart/{id}      → cart.remove        → CartController@remove
DELETE  /cart           → cart.clear         → CartController@clear
GET     /cart/count     → cart.count         → CartController@count
GET     /api/cart       → cart.api           → CartController@apiIndex
```

#### 4. المفضلة (Wishlist) ❤️
```php
GET     /wishlist           → wishlist           → FrontendController@wishlist (يتطلب تسجيل دخول)
POST    /wishlist/toggle    → wishlist.toggle    → WishlistController@toggle
POST    /wishlist/check     → wishlist.check     → WishlistController@check
DELETE  /wishlist/{id}      → wishlist.remove    → WishlistController@remove
```

#### 5. الطلبات (Orders) 📦
```php
GET         /orders         → orders.index      → FrontendController@orders (يتطلب تسجيل دخول)
GET         /order/{id}     → orders.show       → OrderController@show
GET|POST    /orders/track   → orders.track      → OrderController@track
```

#### 6. الدفع (Checkout) 💳
```php
GET     /checkout           → checkout.index    → CheckoutController@index
POST    /checkout/process   → checkout.process  → CheckoutController@process
```

#### 7. المصادقة (Authentication) 🔐
```php
GET     /login              → login             → Auth Login Page
POST    /logout             → logout            → Logout
GET     /register           → register          → Register Page
GET     /user/login         → user.login        → UserAuthController@showLogin
POST    /user/login         → user.login.submit → UserAuthController@login
POST    /user/register      → user.register.submit → UserAuthController@register
POST    /user/logout        → user.logout       → UserAuthController@logout
```

---

### 🔧 Admin Routes

جميع routes الأدمن تبدأ بـ `/admin` وتتطلب تسجيل الدخول و verification

#### 1. إدارة الصفحات (Pages)
```php
GET     /admin/pages                    → admin.pages.index         → PageController@index
GET     /admin/pages/create             → admin.pages.create        → PageController@create
POST    /admin/pages                    → admin.pages.store         → PageController@store
GET     /admin/pages/{page}             → admin.pages.show          → PageController@show
GET     /admin/pages/{page}/edit        → admin.pages.edit          → PageController@edit
PUT     /admin/pages/{page}             → admin.pages.update        → PageController@update
DELETE  /admin/pages/{page}             → admin.pages.destroy       → PageController@destroy
```

#### 2. تحرير صفحات خاصة
```php
GET     /admin/home/edit                → admin.home.edit           → HomePageController@edit
POST    /admin/home/update              → admin.home.update         → HomePageController@update

GET     /admin/about/edit               → admin.about.edit          → AboutPageController@edit
POST    /admin/about/update             → admin.about.update        → AboutPageController@update

GET     /admin/privacy/edit             → admin.privacy.edit        → PrivacyPolicyController@edit
POST    /admin/privacy/update           → admin.privacy.update      → PrivacyPolicyController@update

GET     /admin/contact/edit             → admin.contact.edit        → ContactPageController@edit
POST    /admin/contact/update           → admin.contact.update      → ContactPageController@update
```

#### 3. إدارة الفئات (Categories)
```php
GET     /admin/categories               → admin.categories.index    → CategoryController@index
GET     /admin/categories/create        → admin.categories.create   → CategoryController@create
POST    /admin/categories               → admin.categories.store    → CategoryController@store
GET     /admin/categories/{category}    → admin.categories.show     → CategoryController@show
GET     /admin/categories/{category}/edit → admin.categories.edit   → CategoryController@edit
PUT     /admin/categories/{category}    → admin.categories.update   → CategoryController@update
DELETE  /admin/categories/{category}    → admin.categories.destroy  → CategoryController@destroy
GET     /admin/categories/subcategories → admin.categories.subcategories → CategoryController@getSubcategories
```

#### 4. إدارة المنتجات (Products)
```php
GET     /admin/products                     → admin.products.index      → ProductController@index
GET     /admin/products/create              → admin.products.create     → ProductController@create
POST    /admin/products                     → admin.products.store      → ProductController@store
GET     /admin/products/{product}           → admin.products.show       → ProductController@show
GET     /admin/products/{product}/edit      → admin.products.edit       → ProductController@edit
PUT     /admin/products/{product}           → admin.products.update     → ProductController@update
DELETE  /admin/products/{product}           → admin.products.destroy    → ProductController@destroy
POST    /admin/products/{product}/toggle-status → admin.products.toggle-status → ProductController@toggleStatus
```

#### 5. إدارة الطلبات (Orders) 📊
```php
GET     /admin/orders                       → admin.orders.index        → Admin\OrderController@index
GET     /admin/orders/{id}                  → admin.orders.show         → Admin\OrderController@show
POST    /admin/orders/{id}/update-status    → admin.orders.updateStatus → Admin\OrderController@updateStatus
POST    /admin/orders/{id}/status           → admin.orders.status       → Admin\OrderController@updateStatus
POST    /admin/orders/{id}/payment          → admin.orders.payment      → Admin\OrderController@updatePaymentStatus
GET     /admin/orders/{id}/print            → admin.orders.print        → Admin\OrderController@print
DELETE  /admin/orders/{id}                  → admin.orders.destroy      → Admin\OrderController@destroy
```

#### 6. إدارة القوائم (Menus)
```php
GET     /admin/menus                        → admin.menus.index         → MenuController@index
GET     /admin/menus/create                 → admin.menus.create        → MenuController@create
POST    /admin/menus                        → admin.menus.store         → MenuController@store
GET     /admin/menus/{menu}                 → admin.menus.show          → MenuController@show
GET     /admin/menus/{menu}/edit            → admin.menus.edit          → MenuController@edit
PUT     /admin/menus/{menu}                 → admin.menus.update        → MenuController@update
DELETE  /admin/menus/{menu}                 → admin.menus.destroy       → MenuController@destroy
GET     /admin/menus/{menu}/columns         → admin.menus.columns       → MenuController@manageColumns
POST    /admin/menus/{menu}/columns         → admin.menus.columns.store → MenuController@storeColumn
PUT     /admin/menu-columns/{column}        → admin.menus.columns.update → MenuController@updateColumn
DELETE  /admin/menu-columns/{column}        → admin.menus.columns.destroy → MenuController@destroyColumn
POST    /admin/menu-columns/{column}/items  → admin.menus.items.store   → MenuController@storeItem
DELETE  /admin/menu-items/{item}            → admin.menus.items.destroy → MenuController@destroyItem
```

#### 7. إدارة الأحجام والألوان
```php
# Sizes
GET     /admin/sizes            → admin.sizes.index     → SizeController@index
POST    /admin/sizes            → admin.sizes.store     → SizeController@store
PUT     /admin/sizes/{size}     → admin.sizes.update    → SizeController@update
DELETE  /admin/sizes/{size}     → admin.sizes.destroy   → SizeController@destroy

# Colors
GET     /admin/colors           → admin.colors.index    → ColorController@index
POST    /admin/colors           → admin.colors.store    → ColorController@store
PUT     /admin/colors/{color}   → admin.colors.update   → ColorController@update
DELETE  /admin/colors/{color}   → admin.colors.destroy  → ColorController@destroy

# Shoe Sizes
GET     /admin/shoe-sizes               → admin.shoe-sizes.index    → ShoeSizeController@index
POST    /admin/shoe-sizes               → admin.shoe-sizes.store    → ShoeSizeController@store
PUT     /admin/shoe-sizes/{shoe_size}   → admin.shoe-sizes.update   → ShoeSizeController@update
DELETE  /admin/shoe-sizes/{shoe_size}   → admin.shoe-sizes.destroy  → ShoeSizeController@destroy
```

#### 8. إدارة الرسائل (Contact Messages)
```php
GET     /admin/customers/messages           → admin.customers.messages.index   → ContactMessageController@index
GET     /admin/customers/messages/{id}      → admin.customers.messages.show    → ContactMessageController@show
POST    /admin/customers/messages/{id}/status → admin.customers.messages.status → ContactMessageController@updateStatus
POST    /admin/customers/messages/{id}/reply  → admin.customers.messages.reply  → ContactMessageController@sendReply
DELETE  /admin/customers/messages/{id}      → admin.customers.messages.destroy → ContactMessageController@destroy
```

#### 9. إدارة عناوين الأقسام
```php
GET     /admin/section-titles/edit              → admin.section-titles.edit     → SectionTitleController@edit
POST    /admin/section-titles/update            → admin.section-titles.update   → SectionTitleController@update
GET     /admin/section-titles/get/{key}/{locale?} → admin.section-titles.get    → SectionTitleController@getByKey
```

#### 10. حذف عناصر Discover
```php
DELETE  /admin/discover-items/{id}  → admin.discover-items.destroy  → DiscoverItemController@destroy
```

---

## 🔄 تبديل اللغة
```php
POST    /locale/{locale}    → locale.switch      → تبديل اللغة (ar/en)
```

---

## 🎯 Routes الهامة للتطبيق

### للعملاء (Frontend):
1. **الصفحة الرئيسية**: `/` → `route('home')`
2. **المتجر**: `/shop` → `route('shop')`
3. **السلة**: `/cart` → `route('cart')`
4. **المفضلة**: `/wishlist` → `route('wishlist')`
5. **الطلبات**: `/orders` → `route('orders.index')` ⚠️
6. **الدفع**: `/checkout` → `route('checkout.index')`
7. **تفاصيل المنتج**: `/product/{slug}` → `route('product.details', $slug)`

### للإدارة (Admin):
1. **قائمة الطلبات**: `/admin/orders` → `route('admin.orders.index')`
2. **تفاصيل الطلب**: `/admin/orders/{id}` → `route('admin.orders.show', $id)`
3. **تحديث حالة الطلب**: `POST /admin/orders/{id}/update-status` → `route('admin.orders.updateStatus', $id)`
4. **قائمة المنتجات**: `/admin/products` → `route('admin.products.index')`
5. **تحرير الصفحة الرئيسية**: `/admin/home/edit` → `route('admin.home.edit')`

---

## ⚠️ ملاحظات هامة:

### 1. Routes تتطلب Authentication:
```php
- /cart (يتطلب auth)
- /wishlist (يتطلب auth)
- /orders (يتطلب auth)
- /checkout (يتطلب auth)
- جميع /admin/* routes (تتطلب auth + verified)
```

### 2. Middleware المستخدمة:
```php
- auth: تحقق من تسجيل الدخول
- verified: تحقق من تفعيل البريد الإلكتروني
- SaveIntendedUrl: حفظ URL قبل التوجيه للـ login
```

### 3. AJAX Routes:
هذه Routes تعيد JSON responses:
```php
- POST /cart/add
- POST /wishlist/toggle
- DELETE /wishlist/{id}
- POST /admin/orders/{id}/update-status
- GET /cart/count
- GET /api/cart
```

### 4. API Endpoints:
```php
GET  /api/cart          → إرجاع محتويات السلة بصيغة JSON
GET  /api/user          → إرجاع بيانات المستخدم الحالي
```

---

## 🔧 كيفية استخدام Routes في Blade:

### في Blade Templates:
```blade
<!-- روابط بسيطة -->
<a href="{{ route('home') }}">الرئيسية</a>
<a href="{{ route('shop') }}">المتجر</a>
<a href="{{ route('cart') }}">السلة</a>
<a href="{{ route('wishlist') }}">المفضلة</a>
<a href="{{ route('orders.index') }}">طلباتي</a>

<!-- روابط مع parameters -->
<a href="{{ route('product.details', $product->slug) }}">عرض المنتج</a>
<a href="{{ route('orders.show', $order->id) }}">عرض الطلب</a>
<a href="{{ route('admin.orders.show', $order->id) }}">إدارة الطلب</a>

<!-- في JavaScript -->
<button onclick="window.location.href='{{ route('orders.index') }}'">
    طلباتي
</button>

<!-- في Forms -->
<form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <!-- ... -->
</form>
```

### في Controllers:
```php
// Redirect
return redirect()->route('orders.index');
return redirect()->route('orders.show', $orderId);

// Generate URL
$url = route('orders.index');
$url = route('orders.show', $orderId);

// Check if route exists
if (Route::has('orders.index')) {
    // ...
}
```

---

## ✅ التحديثات التي تمت:

### الملفات المعدلة:
1. ✅ `routes/web.php` - تم تحديث route orders ليصبح `orders.index`
2. ✅ `resources/views/frontend/wishlist.blade.php` - تم تحديث route('orders') → route('orders.index')
3. ✅ `resources/views/frontend/orders.blade.php` - تم تحديث route('orders') → route('orders.index')

### Routes المضافة الجديدة:
- ✅ `POST /admin/orders/{id}/update-status` → admin.orders.updateStatus

---

## 🧪 اختبار Routes:

### اختبار route موجود:
```bash
php artisan route:list --name=orders
php artisan route:list --name=wishlist
php artisan route:list --name=cart
php artisan route:list --name=admin.orders
```

### اختبار في Tinker:
```bash
php artisan tinker
```
```php
>>> route('orders.index')
=> "http://127.0.0.1:8000/orders"

>>> route('admin.orders.index')
=> "http://127.0.0.1:8000/admin/orders"
```

---

## 📝 قواعد تسمية Routes:

### Frontend:
```
resource.action
مثال: orders.index, orders.show, product.details
```

### Admin:
```
admin.resource.action
مثال: admin.orders.index, admin.products.store
```

### API:
```
resource.api أو api.resource
مثال: cart.api
```

---

## الخلاصة:

✅ جميع Routes معرفة ومسجلة بشكل صحيح
✅ تم حل مشكلة `Route [orders.index] not defined`
✅ جميع الملفات محدثة لاستخدام الـ route الصحيح
✅ Admin orders routes كاملة وجاهزة
✅ Frontend orders routes كاملة وجاهزة
✅ Wishlist routes كاملة
✅ Cart routes كاملة

**عدد Routes الإجمالي**: 123 route ✅
