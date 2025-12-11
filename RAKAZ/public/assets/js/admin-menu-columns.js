/**
 * Menu Columns Management - AJAX Operations
 */

$(document).ready(function() {
    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const isArabic = document.documentElement.getAttribute('dir') === 'rtl';

    // Configure AJAX defaults
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // ========================================
    // Add New Column (AJAX)
    // ========================================
    $('#add-column-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        // Disable button and show loading
        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status"></span> ' +
            (isArabic ? 'جاري الإضافة...' : 'Adding...')
        );

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }

                    // Reset form
                    form[0].reset();

                    // Reload page to show new column
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        alert(errorMessage.replace(/<br>/g, '\n'));
                    }
                } else {
                    const msg = isArabic ? 'حدث خطأ أثناء الإضافة' : 'An error occurred while adding';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            }
        });
    });

    // ========================================
    // Update Column (AJAX)
    // ========================================
    $('.edit-column-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status"></span> ' +
            (isArabic ? 'جاري التحديث...' : 'Updating...')
        );

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }

                    // Reload to refresh data
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        alert(errorMessage.replace(/<br>/g, '\n'));
                    }
                } else {
                    const msg = isArabic ? 'حدث خطأ أثناء التحديث' : 'An error occurred while updating';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            }
        });
    });

    // ========================================
    // Add Item to Column (AJAX)
    // ========================================
    $('.add-item-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status"></span>'
        );

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }

                    // Reset form
                    form[0].reset();

                    // Reload to show new item
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    $.each(errors, function(key, value) {
                        errorMessage += value[0] + '<br>';
                    });

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        alert(errorMessage.replace(/<br>/g, '\n'));
                    }
                } else {
                    const msg = isArabic ? 'حدث خطأ أثناء الإضافة' : 'An error occurred while adding';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            }
        });
    });

    // ========================================
    // Delete Column (AJAX with SweetAlert)
    // ========================================
    $('.delete-column-btn').on('click', function(e) {
        e.preventDefault();

        const form = $(this).closest('.delete-column-form');
        const url = form.attr('action');

        // Check if SweetAlert2 is available
        if (typeof Swal === 'undefined') {
            const confirmMsg = isArabic ? 'هل أنت متأكد؟ سيتم حذف العمود وجميع عناصره!' : 'Are you sure? The column and all its items will be deleted!';
            if (!confirm(confirmMsg)) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    }
                },
                error: function(xhr) {
                    const msg = isArabic ? 'حدث خطأ أثناء الحذف' : 'An error occurred while deleting';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            });
            return;
        }

        Swal.fire({
            title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
            text: isArabic ? 'سيتم حذف العمود وجميع عناصره!' : 'The column and all its items will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isArabic ? 'نعم، احذف!' : 'Yes, delete it!',
            cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message);
                            } else {
                                alert(response.message);
                            }
                            setTimeout(function() {
                                window.location.reload();
                            }, 800);
                        }
                    },
                    error: function(xhr) {
                        const msg = isArabic ? 'حدث خطأ أثناء الحذف' : 'An error occurred while deleting';
                        if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });

    // ========================================
    // Delete Item (AJAX with SweetAlert)
    // ========================================
    $('.delete-item-btn').on('click', function(e) {
        e.preventDefault();

        const form = $(this).closest('.delete-item-form');
        const url = form.attr('action');

        // Check if SweetAlert2 is available
        if (typeof Swal === 'undefined') {
            const confirmMsg = isArabic ? 'هل أنت متأكد؟ لن تتمكن من التراجع عن هذا الإجراء!' : 'Are you sure? You won\'t be able to revert this!';
            if (!confirm(confirmMsg)) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    }
                },
                error: function(xhr) {
                    const msg = isArabic ? 'حدث خطأ أثناء الحذف' : 'An error occurred while deleting';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    } else {
                        alert(msg);
                    }
                }
            });
            return;
        }

        Swal.fire({
            title: isArabic ? 'هل أنت متأكد؟' : 'Are you sure?',
            text: isArabic ? 'لن تتمكن من التراجع عن هذا الإجراء!' : 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isArabic ? 'نعم، احذف!' : 'Yes, delete it!',
            cancelButtonText: isArabic ? 'إلغاء' : 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message);
                            } else {
                                alert(response.message);
                            }
                            setTimeout(function() {
                                window.location.reload();
                            }, 800);
                        }
                    },
                    error: function(xhr) {
                        const msg = isArabic ? 'حدث خطأ أثناء الحذف' : 'An error occurred while deleting';
                        if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        } else {
                            alert(msg);
                        }
                    }
                });
            }
        });
    });
});

// Toggle edit form
function toggleEditColumn(columnId) {
    const editForm = document.getElementById('edit-column-' + columnId);
    if (editForm.style.display === 'none' || editForm.style.display === '') {
        editForm.style.display = 'block';
    } else {
        editForm.style.display = 'none';
    }
}
