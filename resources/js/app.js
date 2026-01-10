import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Import jQuery and Select2 (bundled via Vite)
import $ from "jquery";
window.$ = window.jQuery = $;
import "select2/dist/css/select2.min.css";
import "select2";

// Initialize Select2 for supplier form selects if present
document.addEventListener("DOMContentLoaded", function () {
    if (window.jQuery && jQuery().select2) {
        const $kota = jQuery("#kota-select");
        if ($kota.length) {
            $kota.select2({
                tags: true,
                placeholder: "-- Pilih atau ketik kota --",
                allowClear: true,
                width: "100%",
            });
            const oldKota = $kota.data("old");
            if (oldKota) {
                if (
                    $kota.find("option[value='" + oldKota + "']").length === 0
                ) {
                    const newOption = new Option(oldKota, oldKota, true, true);
                    $kota.append(newOption).trigger("change");
                } else {
                    $kota.val(oldKota).trigger("change");
                }
            }
        }

        const $prov = jQuery("#provinsi-select");
        if ($prov.length) {
            $prov.select2({
                tags: false,
                placeholder: "-- Pilih provinsi --",
                allowClear: true,
                width: "100%",
            });
            const oldProv = $prov.data("old");
            if (oldProv) {
                if (
                    $prov.find("option[value='" + oldProv + "']").length === 0
                ) {
                    const newOptionProv = new Option(
                        oldProv,
                        oldProv,
                        true,
                        true
                    );
                    $prov.append(newOptionProv).trigger("change");
                } else {
                    $prov.val(oldProv).trigger("change");
                }
            }
        }
    }
});
