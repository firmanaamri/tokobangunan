import "./bootstrap";

// import Alpine from "alpinejs";
// window.Alpine = Alpine;
// Alpine.start();

// Gunakan event listener ini agar aman bersama Livewire
// document.addEventListener('alpine:init', () => {
    
//     // Jika dulu Anda punya logic sidebar disini, masukkan kembali seperti ini:
//     Alpine.data('layout', () => ({
//         sidebarOpen: true,
//         toggleSidebar() {
//             this.sidebarOpen = !this.sidebarOpen;
//         }
//     }));

// });

// 1. Import jQuery dan assign ke window
// Ini WAJIB agar script di blade ($('#kota-select')...) bisa jalan
import $ from "jquery";
window.$ = window.jQuery = $;

// 2. Import Select2
import "select2/dist/css/select2.min.css";
import select2 from "select2";
// Inisialisasi Select2 agar menempel pada objek jQuery
select2();

// 3. Import SweetAlert2
import Swal from "sweetalert2";
window.Swal = Swal;

// Import SweetAlert2 helper functions
import "./sweetalert-helpers.js";

// CATATAN:
// Logika inisialisasi #kota-select dan #provinsi-select yang sebelumnya ada di sini
// SUDAH DIHAPUS. Logika tersebut sekarang dipindah ke file blade (view)
// karena membutuhkan data mapping dari Config Laravel.