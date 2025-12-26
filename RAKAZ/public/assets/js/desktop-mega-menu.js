/**
 * Desktop Mega Menu Handler
 * يعالج القوائم المنسدلة لأجهزة سطح المكتب فقط
 * - يعرض جميع الأعمدة
 * - يعرض 13 صف فقط لكل عمود
 * - يضيف زر "مشاهدة المزيد" لكل عمود
 */

(function() {
    'use strict';

    // التحقق من أننا على سطح المكتب
    function isDesktop() {
        return window.innerWidth > 1024;
    }

    // إنشاء نصوص ثنائية اللغة
    function createTextSpans(arText, enText) {
        const wrap = document.createDocumentFragment();

        const ar = document.createElement('span');
        ar.className = 'ar-text';
        ar.textContent = arText || '';

        const en = document.createElement('span');
        en.className = 'en-text';
        en.textContent = enText || '';

        wrap.appendChild(ar);
        wrap.appendChild(en);
        return wrap;
    }

    // إضافة عنصر إلى القائمة
    function appendItem(ul, item) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = item.link || '#';
        a.appendChild(createTextSpans(item.name_ar, item.name_en));
        li.appendChild(a);

        // إضافة العناصر الفرعية
        if (Array.isArray(item.children) && item.children.length) {
            const childUl = document.createElement('ul');
            for (const child of item.children) {
                const childLi = document.createElement('li');
                const childA = document.createElement('a');
                childA.href = child.link || '#';
                childA.appendChild(createTextSpans(child.name_ar, child.name_en));
                childLi.appendChild(childA);
                childUl.appendChild(childLi);
            }
            li.appendChild(childUl);
        }

        ul.appendChild(li);
    }

    // إنشاء skeleton loader للعمود
    function createColumnSkeleton() {
        const colEl = document.createElement('div');
        colEl.className = 'dropdown-column skeleton-loading';

        const title = document.createElement('h4');
        title.className = 'dropdown-title skeleton-title';
        title.innerHTML = '<div class="skeleton-line" style="width: 80%; height: 14px;"></div>';
        colEl.appendChild(title);

        const ul = document.createElement('ul');
        for (let i = 0; i < 13; i++) {
            const li = document.createElement('li');
            li.className = 'skeleton-item';
            li.innerHTML = '<div class="skeleton-line" style="width: ' + (60 + Math.random() * 30) + '%; height: 13px;"></div>';
            ul.appendChild(li);
        }
        colEl.appendChild(ul);

        return colEl;
    }

    // بناء القائمة لسطح المكتب مع AJAX
    function buildDesktopMegaMenu(menu) {
        if (!menu || menu.dataset.defer !== '1') return;
        if (menu.dataset.built === '1' || menu.dataset.built === 'building') return;

        const contentEl = menu.querySelector('.js-mega-menu-content');
        const menuId = contentEl ? contentEl.dataset.menuId : null;
        const columnsCount = contentEl ? parseInt(contentEl.dataset.columnsCount) || 3 : 3;

        if (!contentEl || !menuId) {
            menu.dataset.built = '1';
            return;
        }

        menu.dataset.built = 'building';
        contentEl.textContent = '';

        // إضافة skeleton loaders
        const frag = document.createDocumentFragment();
        for (let i = 0; i < columnsCount; i++) {
            frag.appendChild(createColumnSkeleton());
        }
        contentEl.appendChild(frag);

        // تحميل البيانات الفعلية عبر AJAX بعد 1 ميلي ثانية
        setTimeout(() => {
            loadDesktopMenuData(menuId, contentEl, menu);
        }, 1);
    }

    // تحميل بيانات القائمة عبر AJAX
    function loadDesktopMenuData(menuId, contentEl, menu) {
        fetch('/api/desktop-menu/load-items?menu_id=' + menuId)
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    renderDesktopMenu(result.data, contentEl, menuId);
                    menu.dataset.built = '1';
                } else {
                    console.error('Failed to load menu data');
                    contentEl.innerHTML = '<div class="error-message">Failed to load menu</div>';
                    menu.dataset.built = '1';
                }
            })
            .catch(error => {
                console.error('Error loading menu:', error);
                contentEl.innerHTML = '<div class="error-message">Error loading menu</div>';
                menu.dataset.built = '1';
            });
    }

    // رسم القائمة من البيانات المحملة
    function renderDesktopMenu(data, contentEl, menuId) {
        contentEl.textContent = '';
        const frag = document.createDocumentFragment();

        // إنشاء كل عمود
        let hasMoreItems = false;
        for (const col of data) {
            const colEl = document.createElement('div');
            colEl.className = 'dropdown-column';

            // عنوان العمود
            const title = document.createElement('h4');
            title.className = 'dropdown-title';
            title.appendChild(createTextSpans(col.title_ar, col.title_en));
            colEl.appendChild(title);

            // القائمة
            const ul = document.createElement('ul');

            // إضافة العناصر (13 صف كحد أقصى)
            const items = Array.isArray(col.items) ? col.items : [];
            items.forEach(item => {
                appendItem(ul, item);
            });

            colEl.appendChild(ul);
            frag.appendChild(colEl);

            // تحقق إذا كان هناك المزيد
            if (col.has_more) {
                hasMoreItems = true;
            }
        }

        contentEl.appendChild(frag);

        // إضافة زر واحد "مشاهدة المزيد" في الأسفل إذا كان هناك المزيد
        if (hasMoreItems) {
            const viewMoreContainer = document.createElement('div');
            viewMoreContainer.className = 'view-more-container';

            const viewMoreBtn = document.createElement('a');
            viewMoreBtn.className = 'view-more-btn-single';
            viewMoreBtn.href = '#';
            viewMoreBtn.appendChild(createTextSpans('مشاهدة المزيد', 'View More'));

            viewMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.setItem('scrollToMenuId', menuId);
                window.location.href = '/all-menus';
            });

            viewMoreContainer.appendChild(viewMoreBtn);
            contentEl.appendChild(viewMoreContainer);
        }
    }

    // بناء القائمة للهاتف (الكود القديم)
    function buildMobileMegaMenu(menu) {
        if (!menu || menu.dataset.defer !== '1') return;
        if (menu.dataset.built === '1' || menu.dataset.built === 'building') return;

        const dataEl = menu.querySelector('.js-mobile-menu-data');
        const contentEl = menu.querySelector('.js-mega-menu-content');

        if (!dataEl || !contentEl) {
            menu.dataset.built = '1';
            return;
        }

        let data;
        try {
            data = JSON.parse(dataEl.textContent || '[]');
        } catch (e) {
            menu.dataset.built = '1';
            return;
        }

        if (!Array.isArray(data) || data.length === 0) {
            menu.dataset.built = '1';
            return;
        }

        menu.dataset.built = 'building';
        contentEl.textContent = '';

        const frag = document.createDocumentFragment();
        const columnsState = [];

        // إنشاء الأعمدة
        for (const col of data) {
            const colEl = document.createElement('div');
            colEl.className = 'dropdown-column';

            const title = document.createElement('h4');
            title.className = 'dropdown-title';
            title.appendChild(createTextSpans(col.title_ar, col.title_en));
            colEl.appendChild(title);

            const ul = document.createElement('ul');
            colEl.appendChild(ul);

            frag.appendChild(colEl);
            columnsState.push({ ul, items: Array.isArray(col.items) ? col.items : [], idx: 0 });
        }

        contentEl.appendChild(frag);

        // تحميل العناصر على دفعات
        const perChunk = 80;
        const immediate = 40;

        let added = 0;
        while (added < immediate) {
            let progressed = false;
            for (const st of columnsState) {
                if (st.idx < st.items.length) {
                    appendItem(st.ul, st.items[st.idx]);
                    st.idx++;
                    added++;
                    progressed = true;
                    if (added >= immediate) break;
                }
            }
            if (!progressed) break;
        }

        function work(deadline) {
            let count = 0;
            while (count < perChunk && columnsState.some(st => st.idx < st.items.length) && (!deadline || deadline.timeRemaining() > 4)) {
                for (const st of columnsState) {
                    if (st.idx < st.items.length) {
                        appendItem(st.ul, st.items[st.idx]);
                        st.idx++;
                        count++;
                        if (count >= perChunk) break;
                    }
                }
            }

            if (columnsState.some(st => st.idx < st.items.length)) {
                if (window.requestIdleCallback) {
                    window.requestIdleCallback(work, { timeout: 200 });
                } else {
                    window.requestAnimationFrame(() => work(null));
                }
            } else {
                menu.dataset.built = '1';
            }
        }

        if (window.requestIdleCallback) {
            window.requestIdleCallback(work, { timeout: 200 });
        } else {
            window.requestAnimationFrame(() => work(null));
        }
    }

    // الدالة الرئيسية لبناء القائمة - نصدرها إلى window لاستخدامها من script.js
    window.buildDesktopMegaMenu = buildDesktopMegaMenu;
    window.buildMobileMegaMenu = buildMobileMegaMenu;

    // التهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // معالجة القوائم المنسدلة عند التمرير
        const navItems = document.querySelectorAll('.nav-item.dropdown');

        navItems.forEach(function(navItem) {
            const menu = navItem.querySelector('.dropdown-menu.mega-menu');

            navItem.addEventListener('mouseenter', function() {
                if (isDesktop() && menu) {
                    buildDesktopMegaMenu(menu);
                }
            });
        });
    });

})();
