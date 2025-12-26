// Custom Select Dropdown - JavaScript
// بديل كامل للعنصر الافتراضي بدون أي لون أزرق

class CustomSelect {
    constructor(element) {
        this.element = element;
        this.options = this.element.querySelectorAll('option');

        // التحقق من وجود options
        if (this.options.length === 0) {
            console.warn('CustomSelect: No options found in select element');
            return;
        }

        // التأكد من أن selectedIndex صالح
        this.selectedIndex = this.element.selectedIndex;
        if (this.selectedIndex < 0 || this.selectedIndex >= this.options.length) {
            this.selectedIndex = 0;
        }

        this.init();
    }

    init() {
        // التحقق من وجود options قبل المتابعة
        if (!this.options || this.options.length === 0) {
            return;
        }

        // إنشاء wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';

        // إنشاء trigger
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.setAttribute('tabindex', '0');

        const selectedText = document.createElement('span');
        selectedText.className = 'selected-text';

        // التأكد من وجود option قبل الوصول لـ textContent
        var selectedOption = this.options[this.selectedIndex];
        selectedText.textContent = selectedOption ? selectedOption.textContent.trim() : '';

        const arrow = document.createElement('span');
        arrow.className = 'arrow';

        trigger.appendChild(selectedText);
        trigger.appendChild(arrow);

        // إنشاء قائمة الخيارات
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'custom-select-options';

        Array.from(this.options).forEach((option, index) => {
            const customOption = document.createElement('div');
            customOption.className = 'custom-option';
            customOption.textContent = option.textContent;
            customOption.dataset.value = option.value;
            customOption.dataset.index = index;

            if (index === this.selectedIndex) {
                customOption.classList.add('selected');
            }

            // Click event
            customOption.addEventListener('click', (e) => {
                this.selectOption(index);
                e.stopPropagation();
            });

            // Hover effect (رمادي فقط)
            customOption.addEventListener('mouseenter', () => {
                customOption.style.backgroundColor = '#f0f0f0';
            });

            customOption.addEventListener('mouseleave', () => {
                if (!customOption.classList.contains('selected')) {
                    customOption.style.backgroundColor = '';
                }
            });

            optionsContainer.appendChild(customOption);
        });

        // تجميع العناصر
        wrapper.appendChild(trigger);
        // Keep options within wrapper to avoid global overlay artifacts
        wrapper.appendChild(optionsContainer);

        // إخفاء العنصر الأصلي
        this.element.style.display = 'none';
        this.element.parentNode.insertBefore(wrapper, this.element);
        wrapper.appendChild(this.element);

        // حفظ المراجع
        this.wrapper = wrapper;
        this.trigger = trigger;
        this.selectedTextElement = selectedText;
        this.optionsContainer = optionsContainer;
        this.customOptions = optionsContainer.querySelectorAll('.custom-option');

        // الأحداث
        this.attachEvents();
    }

    attachEvents() {
        // فتح/إغلاق القائمة
        this.trigger.addEventListener('click', (e) => {
            this.toggle();
            e.stopPropagation();
        });

        // إغلاق عند الضغط خارج القائمة
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.close();
            }
        });

        // Close on resize
        window.addEventListener('resize', () => {
            if (this.optionsContainer.classList.contains('active')) this.close();
        });

        // Close on scroll - BUT NOT when scrolling inside the options container or modal
        const scrollHandler = (e) => {
            // Don't close if scrolling inside the options container
            if (this.optionsContainer.contains(e.target)) {
                return;
            }
            // Don't close if scrolling inside the wrapper
            if (this.wrapper.contains(e.target)) {
                return;
            }
            // Don't close if scrolling inside a modal
            const modal = this.wrapper.closest('.modal');
            if (modal && modal.contains(e.target)) {
                return;
            }
            // Close only if scrolling outside
            if (this.optionsContainer.classList.contains('active')) {
                this.close();
            }
        };

        // Use capture phase to catch scroll events early
        window.addEventListener('scroll', scrollHandler, true);
        document.addEventListener('scroll', scrollHandler, true);

        // Prevent scroll event from closing the dropdown when scrolling inside options
        this.optionsContainer.addEventListener('scroll', (e) => {
            e.stopPropagation();
        });

        // Prevent wheel event from propagating when scrolling inside options
        this.optionsContainer.addEventListener('wheel', (e) => {
            e.stopPropagation();
        }, { passive: false });

        // Prevent click inside options from closing (already handled in option click)
        this.optionsContainer.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Keyboard navigation
        this.trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.toggle();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectNext();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectPrevious();
            } else if (e.key === 'Escape') {
                this.close();
            }
        });

        // منع أي لون أزرق
        this.trigger.addEventListener('focus', () => {
            this.trigger.style.outline = 'none';
            this.trigger.style.borderColor = '#333';
        });
    }

    toggle() {
        if (this.optionsContainer.classList.contains('active')) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        // إغلاق جميع القوائم الأخرى
        document.querySelectorAll('.custom-select-options.active').forEach(el => {
            if (el !== this.optionsContainer) {
                el.classList.remove('active');
                // Find the trigger associated with this options container (not easy directly, but we can manage)
                // Actually, we should rely on the instance close() if possible, but for now just remove classes
            }
        });

        // Ensure options menu matches trigger width
        const rect = this.trigger.getBoundingClientRect();
        this.optionsContainer.style.width = rect.width + 'px';

        this.optionsContainer.classList.add('active');
        this.trigger.classList.add('active');
    }

    close() {
        this.optionsContainer.classList.remove('active');
        this.trigger.classList.remove('active');
    }

    selectOption(index) {
        // تحديث العنصر الأصلي
        this.element.selectedIndex = index;
        this.selectedIndex = index;

        // إطلاق حدث change
        const event = new Event('change', { bubbles: true });
        this.element.dispatchEvent(event);

        // تحديث النص المعروض
        this.selectedTextElement.textContent = this.options[index].textContent;

        // تحديث الفئات
        this.customOptions.forEach((option, i) => {
            if (i === index) {
                option.classList.add('selected');
                option.style.backgroundColor = '#f5f5f5';
            } else {
                option.classList.remove('selected');
                option.style.backgroundColor = '';
            }
        });

        this.close();
    }

    selectNext() {
        const newIndex = Math.min(this.selectedIndex + 1, this.options.length - 1);
        if (newIndex !== this.selectedIndex) {
            this.selectOption(newIndex);
        }
    }

    selectPrevious() {
        const newIndex = Math.max(this.selectedIndex - 1, 0);
        if (newIndex !== this.selectedIndex) {
            this.selectOption(newIndex);
        }
    }

    destroy() {
        this.wrapper.parentNode.insertBefore(this.element, this.wrapper);
        this.wrapper.remove();
        this.optionsContainer.remove();
        this.element.style.display = '';
    }
}

// تهيئة تلقائية لجميع القوائم المنسدلة
document.addEventListener('DOMContentLoaded', function() {
    // تطبيق على جميع القوائم المنسدلة
    const selects = document.querySelectorAll('.option-select, select.form-select, .custom-select');

    selects.forEach(select => {
        // تخطي إذا كان قد تم تحويله مسبقاً
        if (select.parentElement.classList.contains('custom-select-wrapper')) {
            return;
        }

        new CustomSelect(select);
    });

    // مراقبة العناصر الجديدة
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // Element node
                    const selects = node.querySelectorAll('.option-select, select.form-select, .custom-select');
                    selects.forEach(select => {
                        if (!select.parentElement.classList.contains('custom-select-wrapper')) {
                            new CustomSelect(select);
                        }
                    });
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Export للاستخدام في ملفات أخرى
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CustomSelect;
}
