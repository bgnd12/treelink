<footer class="bg-ink-950 text-ink-300 py-14">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-lg text-white">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                    TreeLink
                </a>
                <p class="mt-3 text-sm max-w-xs text-ink-400">Satu link untuk semua kontenmu. Bangun halaman link-in-bio yang cantik dalam hitungan menit.</p>
            </div>
            <div>
                <p class="text-white font-semibold text-sm mb-3">Produk</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#features" class="hover:text-white">Fitur</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Daftar Gratis</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold text-sm mb-3">Perusahaan</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#faq" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-white">Syarat Layanan</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-white/10 text-xs text-ink-500 flex flex-col sm:flex-row justify-between gap-2">
            <p>&copy; {{ date('Y') }} TreeLink. Seluruh hak cipta dilindungi.</p>
            <p>Dibuat dengan Laravel &amp; Tailwind CSS.</p>
        </div>
    </div>
</footer>
