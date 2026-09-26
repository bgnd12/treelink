<footer id="contact" class="bg-ink-950 text-ink-300 py-14">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-lg text-white">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white text-sm">🔗</span>
                    TreeLink
                </a>
                <p class="mt-3 text-sm max-w-xs text-ink-400">Find friends, mentors, and communities that help you grow together.</p>
                <div class="flex items-center gap-2 mt-5">
                    <a href="#" aria-label="Instagram" class="footer-social">◎</a>
                    <a href="#" aria-label="TikTok" class="footer-social">♪</a>
                    <a href="#" aria-label="YouTube" class="footer-social">▶</a>
                    <a href="#" aria-label="LinkedIn" class="footer-social">in</a>
                </div>
            </div>
            <div>
                <p class="text-white font-semibold text-sm mb-3">Product</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#features" class="hover:text-white">Features</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Get Started</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Log in</a></li>
                </ul>
            </div>
            <div>
                <p class="text-white font-semibold text-sm mb-3">Company</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}#how-it-works" class="hover:text-white">How It Works</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-white/10 text-xs text-ink-500 flex flex-col sm:flex-row justify-between gap-2">
                <p>&copy; {{ date('Y') }} TreeLink. All rights reserved.</p>
            <p>Built with Laravel &amp; Tailwind CSS.</p>
        </div>
    </div>
</footer>
