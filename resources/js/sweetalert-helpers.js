// Global SweetAlert2 Helper Functions

// Generic delete confirmation
window.confirmDelete = function (formId, itemName) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        html: `Data <strong>${itemName}</strong> akan dihapus permanen dan tidak dapat dikembalikan!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Supplier delete confirmation
window.confirmDeleteSupplier = function (form) {
    Swal.fire({
        title: "Hapus Supplier?",
        text: "Data supplier akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

// Purchase delete confirmation
window.confirmDeletePurchase = function (form) {
    Swal.fire({
        title: "Hapus Transaksi Pembelian?",
        text: "Data transaksi akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

// Purchase Request delete confirmation
window.confirmDeletePR = function (formId) {
    Swal.fire({
        title: "Hapus Purchase Request?",
        text: "Data PR akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Reject PR confirmation
window.confirmRejectPR = function (form) {
    Swal.fire({
        title: "Tolak Purchase Request?",
        text: "Staff akan menerima notifikasi penolakan PR ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-times-circle mr-2"></i>Ya, Tolak!',
        cancelButtonText: '<i class="fas fa-arrow-left mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Ensure required fields are valid before submitting.
            // Using checkValidity/reportValidity because form.submit() bypasses HTML5 validation.
            if (typeof form.checkValidity === "function") {
                if (!form.checkValidity()) {
                    // Show browser validation UI
                    if (typeof form.reportValidity === "function") {
                        form.reportValidity();
                    }
                    // Show a helpful toast as well
                    Swal.fire({
                        icon: "error",
                        title: "Validasi diperlukan",
                        text: "Silakan isi alasan penolakan sebelum melanjutkan.",
                        showConfirmButton: true,
                        confirmButtonText: "Tutup",
                        customClass: { popup: "rounded-xl" },
                    });
                    return;
                }
            }
            form.submit();
        }
    });
};

// Approve PR confirmation
window.confirmApprovePR = function (form) {
    Swal.fire({
        title: "Setujui Purchase Request?",
        text: "Dengan menyetujui, sistem akan membuat Purchase Order (PO) secara otomatis.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#10b981",
        cancelButtonColor: "#6b7280",
        confirmButtonText:
            '<i class="fas fa-check-circle mr-2"></i>Ya, Setujui & Buat PO',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

// Save/Update confirmation
window.confirmSave = function (form, itemType = "data") {
    Swal.fire({
        title: "Simpan Perubahan?",
        text: `Perubahan pada ${itemType} akan disimpan.`,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#10b981",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Ya, Simpan!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

// Create/Add confirmation
window.confirmCreate = function (form, itemType = "data") {
    Swal.fire({
        title: "Tambah " + itemType + " Baru?",
        text: `${itemType} baru akan ditambahkan ke sistem.`,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#10b981",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-plus mr-2"></i>Ya, Tambah!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // Respect HTML5 validation before submitting
            if (typeof form.checkValidity === "function") {
                if (!form.checkValidity()) {
                    if (typeof form.reportValidity === "function") {
                        form.reportValidity();
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validasi diperlukan",
                        text: "Silakan lengkapi form sebelum melanjutkan.",
                        showConfirmButton: true,
                        confirmButtonText: "Tutup",
                        customClass: { popup: "rounded-xl" },
                    });
                    return;
                }
            }
            form.submit();
        }
    });
};

// Success toast notification
window.showSuccessToast = function (message) {
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: message,
        showConfirmButton: true,
        confirmButtonText: "OK",
        customClass: { popup: "rounded-xl" },
    });
};

// Error toast notification
window.showErrorToast = function (message) {
    Swal.fire({
        icon: "error",
        title: "Gagal!",
        text: message,
        showConfirmButton: true,
        confirmButtonText: "Tutup",
        customClass: { popup: "rounded-xl" },
    });
};

// Logout confirmation
window.confirmLogout = function (form) {
    Swal.fire({
        title: "Keluar dari akun?",
        text: "Anda akan keluar dari sesi ini dan harus login kembali.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Keluar',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: "rounded-xl",
            confirmButton: "font-bold px-6 py-2 rounded-lg",
            cancelButton: "font-bold px-6 py-2 rounded-lg",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};
