# نظام إدارة المستخدمين والعملاء والمسؤولين - RAKAZ
# Users, Customers & Administrators Management System

## 📋 نظرة عامة | Overview

تم إنشاء نظام إدارة شامل لـ:
- **المستخدمين (Users)**: جميع مستخدمي النظام
- **العملاء (Customers)**: المستخدمين الذين لديهم طلبات
- **المسؤولين (Administrators)**: المستخدمين بصلاحيات إدارية

---

## 🎯 المميزات | Features

### 1. إدارة المستخدمين (Users Management)
✅ عرض جميع المستخدمين مع إحصائيات شاملة
✅ البحث بالاسم، البريد الإلكتروني، أو رقم المستخدم
✅ إضافة مستخدمين جدد
✅ تعديل بيانات المستخدمين
✅ حذف المستخدمين (مع حذف جميع الطلبات)
✅ تفعيل/إلغاء تفعيل التحقق من البريد الإلكتروني
✅ عرض تفاصيل المستخدم مع سجل الطلبات
✅ Pagination مع خيارات عرض متعددة (15/25/50/100)

### 2. إدارة العملاء (Customers Management)
✅ عرض العملاء (المستخدمين الذين لديهم طلبات فقط)
✅ فلترة العملاء: الكل / نشطون / غير نشطون
✅ إحصائيات شاملة: إجمالي العملاء، النشطون، الإيرادات، متوسط قيمة الطلب
✅ عرض تفاصيل العميل مع سجل الطلبات الكامل
✅ حساب إجمالي الإنفاق لكل عميل
✅ عرض تاريخ آخر طلب
✅ حذف العميل مع جميع طلباته

### 3. إدارة المسؤولين (Administrators Management)
✅ عرض جميع المسؤولين
✅ إضافة مسؤولين جدد مع صلاحيات كاملة
✅ تعديل بيانات المسؤولين
✅ حذف المسؤولين (مع حماية من حذف الحساب الخاص)
✅ تفعيل/إلغاء تفعيل التحقق من البريد الإلكتروني
✅ عرض تفاصيل المسؤول والصلاحيات

---

## 📊 الإحصائيات المتاحة | Available Statistics

### إحصائيات المستخدمين:
- إجمالي المستخدمين
- المستخدمين المحققين
- المستخدمين غير المحققين
- المستخدمين الذين لديهم طلبات
- المستخدمين الجدد هذا الشهر

### إحصائيات العملاء:
- إجمالي العملاء
- العملاء النشطون (طلب خلال آخر 30 يوم)
- إجمالي الطلبات
- إجمالي الإيرادات
- متوسط قيمة الطلب

### إحصائيات المسؤولين:
- إجمالي المسؤولين
- المسؤولين المحققين
- المسؤولين غير المحققين
- المسؤولين الجدد هذا الشهر

---

## 🗂️ الملفات المُنشأة | Created Files

### Controllers:
```
app/Http/Controllers/Admin/
├── UserManagementController.php (173 lines)
├── CustomerManagementController.php (110 lines)
└── AdministratorController.php (220 lines)
```

### Views - Users:
```
resources/views/admin/users/
├── index.blade.php (400+ lines) - قائمة المستخدمين
├── create.blade.php (95 lines) - إضافة مستخدم
├── edit.blade.php (95 lines) - تعديل مستخدم
└── show.blade.php (200+ lines) - تفاصيل المستخدم
```

### Views - Customers:
```
resources/views/admin/customers/
├── index.blade.php (350+ lines) - قائمة العملاء
└── show.blade.php (300+ lines) - تفاصيل العميل
```

### Views - Administrators:
```
resources/views/admin/administrators/
├── index.blade.php (380+ lines) - قائمة المسؤولين
├── create.blade.php (85 lines) - إضافة مسؤول
├── edit.blade.php (95 lines) - تعديل مسؤول
└── show.blade.php (250+ lines) - تفاصيل المسؤول
```

### Migration:
```
database/migrations/
└── 2025_12_21_024328_add_is_admin_to_users_table.php
```

### Updated Files:
```
routes/web.php - Added all routes
app/Models/User.php - Added is_admin field and relationships
resources/views/admin/partials/sidebar.blade.php - Added navigation menu
```

---

## 🔗 الروابط | Routes

### User Management Routes:
```
GET     /admin/users                          - List all users
GET     /admin/users/create                   - Create user form
POST    /admin/users                          - Store new user
GET     /admin/users/{id}                     - Show user details
GET     /admin/users/{id}/edit                - Edit user form
PUT     /admin/users/{id}                     - Update user
DELETE  /admin/users/{id}                     - Delete user
POST    /admin/users/{id}/toggle-verification - Toggle email verification
```

### Customer Management Routes:
```
GET     /admin/customers                      - List all customers
GET     /admin/customers?filter=active        - Active customers only
GET     /admin/customers?filter=inactive      - Inactive customers only
GET     /admin/customers/{id}                 - Show customer details
DELETE  /admin/customers/{id}                 - Delete customer
```

### Administrator Management Routes:
```
GET     /admin/administrators                 - List all administrators
GET     /admin/administrators/create          - Create administrator form
POST    /admin/administrators                 - Store new administrator
GET     /admin/administrators/{id}            - Show administrator details
GET     /admin/administrators/{id}/edit       - Edit administrator form
PUT     /admin/administrators/{id}            - Update administrator
DELETE  /admin/administrators/{id}            - Delete administrator (except self)
POST    /admin/administrators/{id}/toggle-verification - Toggle email verification
```

---

## 📱 Sidebar Navigation

تم إضافة قسم جديد في Sidebar:

```
📋 إدارة المستخدمين (Users Management)
├── 👥 المستخدمين (Users)
│   ├── جميع المستخدمين (All Users)
│   └── إضافة مستخدم (Add User)
├── 🛒 العملاء (Customers)
└── 🛡️ المسؤولين (Administrators)
```

---

## 🎨 التصميم | Design

### المميزات البصرية:
✅ **بطاقات إحصائيات** مع تدرجات لونية جذابة
✅ **صور شخصية (Avatars)** تلقائية بالحرف الأول من الاسم
✅ **شارات الحالة (Status Badges)** للتحقق من البريد
✅ **أيقونات SVG** احترافية من Font Awesome
✅ **Hover Effects** و Transitions سلسة
✅ **Responsive Design** للأجهزة المختلفة
✅ **SweetAlert2** للتأكيدات والإشعارات
✅ **Pagination** احترافي مع عداد العناصر

### الألوان المستخدمة:
- **Primary**: #667eea → #764ba2 (Gradient Purple)
- **Secondary**: #f093fb → #f5576c (Gradient Pink)
- **Info**: #4facfe → #00f2fe (Gradient Blue)
- **Warning**: #fa709a → #fee140 (Gradient Yellow)
- **Success**: Green badges
- **Danger**: Red delete buttons

---

## 🗃️ قاعدة البيانات | Database

### جدول Users:
```sql
- id (Primary Key)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- email_verified_at (TIMESTAMP, NULLABLE)
- password (VARCHAR)
- is_admin (BOOLEAN, DEFAULT: 0)  ← جديد
- remember_token (VARCHAR)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### العلاقات (Relationships):
```php
User hasMany Orders
User hasMany Wishlists
User hasMany Carts
```

---

## 📈 البيانات الحالية | Current Data

```
Total Users: 5,002
├── Administrators: 3
│   ├── Super Admin (admin@rakaz.com)
│   ├── Ahmed Ali (ahmed@rakaz.com)
│   └── Sara Mohammed (sara@rakaz.com)
└── Regular Users: 4,999

Total Orders: 5,001
Users with Orders (Customers): 2,251
Active Customers (Last 30 days): [Dynamic]
```

---

## 🔐 الأمان | Security

✅ **CSRF Protection** على جميع النماذج
✅ **Password Hashing** باستخدام bcrypt
✅ **Email Validation** لجميع الإدخالات
✅ **Authentication Middleware** للـ admin routes
✅ **Self-Delete Protection** للمسؤولين
✅ **Comprehensive Logging** لجميع العمليات
✅ **Try-Catch Blocks** لمعالجة الأخطاء

---

## 🚀 الاستخدام | Usage

### الوصول للنظام:
```
1. قم بتسجيل الدخول كمسؤول
2. من Sidebar اختر "إدارة المستخدمين"
3. اختر القسم المطلوب:
   - المستخدمين: لإدارة جميع المستخدمين
   - العملاء: للمستخدمين الذين لديهم طلبات
   - المسؤولين: لإدارة المسؤولين
```

### إضافة مستخدم جديد:
```
1. اذهب إلى إدارة المستخدمين > إضافة مستخدم
2. أدخل البيانات المطلوبة
3. اختر حالة التحقق من البريد
4. اضغط حفظ
```

### حذف مستخدم:
```
1. من قائمة المستخدمين، اضغط زر الحذف 🗑️
2. أكد الحذف في نافذة SweetAlert2
3. سيتم حذف المستخدم وجميع طلباته
```

### البحث عن مستخدم:
```
1. استخدم حقل البحث في أعلى الصفحة
2. ابحث بـ: الاسم، البريد الإلكتروني، أو رقم المستخدم
3. اضغط Enter أو زر البحث
```

---

## 🧪 الاختبار | Testing

### Test Credentials:
```
Email: admin@rakaz.com
Password: password123

Email: ahmed@rakaz.com
Password: password123

Email: sara@rakaz.com
Password: password123
```

### تنظيف الـ Cache:
```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

## 📝 ملاحظات مهمة | Important Notes

1. **العلاقة بين Users و Customers:**
   - Users = جميع المستخدمين في النظام
   - Customers = المستخدمين الذين لديهم طلبات فقط
   - Customer هو User له orders_count > 0

2. **حذف المستخدمين:**
   - حذف User سيحذف جميع الطلبات المرتبطة به
   - حذف Customer سيحذف المستخدم وطلباته

3. **المسؤولين:**
   - لا يمكن للمسؤول حذف حسابه الخاص
   - يمكن إضافة مسؤولين جدد من صفحة الإدارة
   - جميع المسؤولين لديهم صلاحيات كاملة

4. **الإحصائيات:**
   - يتم حسابها في الـ Controller للأداء الأفضل
   - Active Customers = طلب خلال آخر 30 يوم
   - New This Month = مضاف خلال الشهر الحالي

---

## 🎯 المميزات الإضافية المقترحة | Future Enhancements

- [ ] تصدير البيانات إلى Excel/CSV
- [ ] نظام الأدوار والصلاحيات (Roles & Permissions)
- [ ] سجل النشاطات (Activity Log)
- [ ] إرسال إشعارات للمستخدمين
- [ ] تقارير مفصلة عن العملاء
- [ ] Bulk Actions (حذف/تفعيل متعدد)
- [ ] Advanced Filters (بالتاريخ، الحالة، إلخ)
- [ ] Dashboard Charts للإحصائيات

---

## 👨‍💻 المطور | Developer

تم التطوير بواسطة: GitHub Copilot  
التاريخ: 21 ديسمبر 2025  
Laravel Version: 10+  
PHP Version: 8.1+

---

## 📞 الدعم | Support

للمساعدة أو الاستفسارات، يرجى التواصل عبر:
- GitHub Issues
- Email Support

---

**✨ النظام جاهز للاستخدام بالكامل! ✨**
**🎉 System is Fully Ready to Use! 🎉**
