# Menu Management System Documentation

## Overview
The menu management system allows you to create and manage dynamic navigation menus with mega dropdown support for the RAKAZ website. This system integrates seamlessly with the existing category system.

## Database Structure

### Tables Created
1. **menus** - Main navigation menu items
   - `id`: Primary key
   - `name`: JSON field `{"ar": "value", "en": "value"}` for menu name
   - `image`: Path to promotional image shown in mega menu
   - `image_title`: JSON field for image title (AR/EN)
   - `image_description`: JSON field for image description (AR/EN)
   - `link`: Optional direct link (if no dropdown)
   - `sort_order`: Display order
   - `is_active`: Status
   - `timestamps`

2. **menu_columns** - Columns within mega menu dropdowns
   - `id`: Primary key
   - `menu_id`: Foreign key to menus table
   - `title`: JSON field `{"ar": "value", "en": "value"}` for column title
   - `sort_order`: Display order
   - `is_active`: Status
   - `timestamps`

3. **menu_column_items** - Links within columns
   - `id`: Primary key
   - `menu_column_id`: Foreign key to menu_columns table
   - `category_id`: Optional foreign key to categories table
   - `custom_name`: JSON field for custom link name (AR/EN)
   - `custom_link`: Optional custom URL
   - `sort_order`: Display order
   - `is_active`: Status
   - `timestamps`

## Features

### 1. Mega Menu Support
- Each menu can have multiple dropdown columns
- Each column contains multiple links
- Promotional image can be displayed in mega menu
- Fully supports Arabic/English localization

### 2. Flexible Link System
Menu items can either:
- **Link to Categories**: Automatically use category name and route
- **Custom Links**: Define custom name and URL
- **Both**: Fallback logic (custom → category)

### 3. Multi-Language Support
All text fields support both Arabic and English:
- Menu names
- Column titles
- Item names
- Image titles and descriptions

### 4. Image Management
- Upload promotional images for mega menus
- Automatic image deletion on update/removal
- Image preview on upload
- Stored in `storage/app/public/menus/`

## Admin Interface

### Routes
```php
// Menu CRUD
GET     /admin/menus              → admin.menus.index
GET     /admin/menus/create       → admin.menus.create
POST    /admin/menus              → admin.menus.store
GET     /admin/menus/{id}/edit    → admin.menus.edit
PUT     /admin/menus/{id}         → admin.menus.update
DELETE  /admin/menus/{id}         → admin.menus.destroy

// Column Management
GET     /admin/menus/{id}/columns           → admin.menus.columns
POST    /admin/menus/{id}/columns           → admin.menus.columns.store
PUT     /admin/menu-columns/{id}            → admin.menus.columns.update
DELETE  /admin/menu-columns/{id}            → admin.menus.columns.destroy

// Item Management
POST    /admin/menu-columns/{id}/items      → admin.menus.items.store
DELETE  /admin/menu-items/{id}              → admin.menus.items.destroy
```

### Admin Pages
1. **Index** (`admin.menus.index`)
   - List all menus
   - Show column count, status, image
   - Actions: Edit, Manage Columns, Delete

2. **Create** (`admin.menus.create`)
   - Add new menu
   - Fields: Name (AR/EN), Link, Image, Image title/description, Sort order, Status

3. **Edit** (`admin.menus.edit`)
   - Update existing menu
   - Same fields as create
   - Option to remove current image

4. **Manage Columns** (`admin.menus.columns`)
   - Add/Edit/Delete columns for a menu
   - Add/Delete items in each column
   - Link items to categories or create custom links
   - Real-time form field toggling

## Models

### Menu Model
```php
// Relationships
hasMany(MenuColumn) - columns
hasMany(MenuColumn::where('is_active', 1)) - activeColumns

// Methods
getName($locale)              → Returns menu name in specified language
getImageTitle($locale)        → Returns image title in specified language
getImageDescription($locale)  → Returns image description in specified language

// Scopes
active()    → Only active menus
ordered()   → Sorted by sort_order
```

### MenuColumn Model
```php
// Relationships
belongsTo(Menu) - menu
hasMany(MenuColumnItem) - items
hasMany(MenuColumnItem::where('is_active', 1)) - activeItems

// Methods
getTitle($locale) → Returns column title in specified language

// Scopes
active()    → Only active columns
ordered()   → Sorted by sort_order
```

### MenuColumnItem Model
```php
// Relationships
belongsTo(MenuColumn) - column
belongsTo(Category) - category (optional)

// Methods
getName($locale)  → Returns custom name OR category name (fallback)
getLink()         → Returns custom link OR category route (fallback)

// Scopes
active()    → Only active items
ordered()   → Sorted by sort_order
```

## Usage Example

### Creating a Menu Structure

**Example: "KANDORAS" Menu**

1. **Create Menu**
   - Name AR: "الكنادير"
   - Name EN: "KANDORAS"
   - Link: (leave empty for dropdown)
   - Image: Upload promotional image
   - Image Title AR: "تشكيلة الكنادير الجديدة"
   - Image Title EN: "New Kandoras Collection"

2. **Add Columns**
   - Column 1: "أنواع الكنادير / TYPES"
   - Column 2: "المناسبات / OCCASIONS"
   - Column 3: "الخامات / MATERIALS"

3. **Add Items to Each Column**
   
   **Column 1 (TYPES):**
   - Item 1: Link to Category "Traditional Kandoras" (category_id = 1)
   - Item 2: Link to Category "Modern Kandoras" (category_id = 2)
   - Item 3: Custom Link "Special Edition" → /special-kandoras
   
   **Column 2 (OCCASIONS):**
   - Item 1: Link to Category "Wedding Kandoras" (category_id = 5)
   - Item 2: Link to Category "Daily Kandoras" (category_id = 6)
   
   **Column 3 (MATERIALS):**
   - Item 1: Link to Category "Cotton" (category_id = 8)
   - Item 2: Link to Category "Silk" (category_id = 9)

## Frontend Integration

### Fetching Menus
```php
// In your navigation controller/view
$menus = Menu::active()
    ->ordered()
    ->with([
        'activeColumns.activeItems.category'
    ])
    ->get();
```

### Displaying in Blade
```blade
<nav class="main-nav">
    @foreach($menus as $menu)
        <div class="nav-item">
            @if($menu->link)
                <a href="{{ $menu->link }}">
                    {{ $menu->getName(app()->getLocale()) }}
                </a>
            @else
                <button class="dropdown-toggle">
                    {{ $menu->getName(app()->getLocale()) }}
                </button>
                
                <!-- Mega Menu Dropdown -->
                <div class="mega-menu">
                    <div class="columns">
                        @foreach($menu->activeColumns as $column)
                            <div class="column">
                                <h3>{{ $column->getTitle(app()->getLocale()) }}</h3>
                                <ul>
                                    @foreach($column->activeItems as $item)
                                        <li>
                                            <a href="{{ $item->getLink() }}">
                                                {{ $item->getName(app()->getLocale()) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Promotional Image -->
                    @if($menu->image)
                        <div class="promo-image">
                            <img src="{{ Storage::url($menu->image) }}" 
                                 alt="{{ $menu->getImageTitle(app()->getLocale()) }}">
                            <h4>{{ $menu->getImageTitle(app()->getLocale()) }}</h4>
                            <p>{{ $menu->getImageDescription(app()->getLocale()) }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</nav>
```

## Translation Keys

All labels are defined in `resources/lang/{ar,en}/labels.php`:

### Arabic Labels
- `labels.menus.title` → "إدارة القوائم"
- `labels.menus.add_new` → "إضافة قائمة جديدة"
- `labels.menus.manage_columns` → "إدارة الأعمدة"
- etc.

### English Labels
- `labels.menus.title` → "Menu Management"
- `labels.menus.add_new` → "Add New Menu"
- `labels.menus.manage_columns` → "Manage Columns"
- etc.

## Notes

### Important Considerations
1. **Category Integration**: The system automatically pulls active categories for linking
2. **Cascading Deletes**: Deleting a menu deletes all its columns and items
3. **Image Storage**: Images stored in `storage/app/public/menus/` with automatic cleanup
4. **Fallback Logic**: MenuColumnItem intelligently falls back from custom to category data
5. **Multi-Language**: All text fields use JSON structure for AR/EN support

### Best Practices
1. **Sort Order**: Use incremental numbers (0, 10, 20, 30) to allow easy reordering
2. **Custom vs Category Links**: 
   - Use category links when linking to existing categories
   - Use custom links for special pages or external URLs
3. **Image Optimization**: Keep mega menu images under 200KB for fast loading
4. **Column Count**: Limit to 3-4 columns per menu for better UX

## Testing

### Manual Testing Checklist
- [ ] Create a menu with name, link, and image
- [ ] Add columns to the menu
- [ ] Add items linked to categories
- [ ] Add items with custom names/links
- [ ] Edit menu details
- [ ] Update column titles
- [ ] Delete items
- [ ] Delete columns
- [ ] Delete menu
- [ ] Verify image upload/deletion
- [ ] Test with AR/EN language switching
- [ ] Verify frontend display

## File Structure

```
app/
├── Models/
│   ├── Menu.php
│   ├── MenuColumn.php
│   └── MenuColumnItem.php
├── Http/Controllers/Admin/
│   └── MenuController.php
database/migrations/
└── 2025_12_10_100000_create_menus_tables.php
resources/
├── lang/
│   ├── ar/labels.php
│   └── en/labels.php
└── views/admin/menus/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── columns.blade.php
routes/
└── web.php (menu routes added)
```

## Next Steps

To complete the frontend integration:
1. Update navigation partial to read from database
2. Style mega menu dropdowns
3. Implement responsive design for mobile
4. Add hover/click interactions
5. Test with live data

---

**System Status**: ✅ Backend Complete | ⏳ Frontend Integration Pending
**Last Updated**: December 10, 2025
