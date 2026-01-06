<footer class="mt-auto bg-gradient-to-r from-[#C5ADC5] via-[#B2B5E0] to-[#C5ADC5] border-t shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Section 1: About Company -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Toko Bangunan Jaya Prana</h3>
                <p class="text-sky-50 text-sm leading-relaxed">
                    Menyediakan berbagai kebutuhan material bangunan berkualitas dengan harga terjangkau dan pelayanan terbaik.
                </p>
            </div>

            <!-- Section 2: Quick Links -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Menu Cepat</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-sky-50 hover:text-white transition-colors text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stokbarang') }}" class="text-sky-50 hover:text-white transition-colors text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Stok Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="text-sky-50 hover:text-white transition-colors text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a4 4 0 00-8 0H6a2 2 0 00-2 2v2h16V5a2 2 0 00-2-2z" />
                            </svg>
                            Supplier
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Section 3: Contact Info -->
            <div>
                <h3 class="text-white font-bold text-lg mb-4">Kontak Kami</h3>
                <ul class="space-y-2 text-sm text-sky-50">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Jl. Raya Bangunan No. 123, Kota Anda</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>(021) 1234-5678</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>info@jayaprana.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-white/20 mt-8 pt-6">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <p class="text-sky-50 text-sm">
                    &copy; {{ date('Y') }} <span class="font-semibold">Toko Bangunan Jaya Prana</span>. All rights reserved.
                </p>
                <p class="text-sky-100 text-xs mt-2 md:mt-0">
                    Version 1.0.0
                </p>
            </div>
        </div>
    </div>
</footer>
