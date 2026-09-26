<?php $__env->startSection('title', config('app.name').' — Satu Link untuk Semua Kontenmu'); ?>
<?php $__env->startSection('description', 'Buat halaman link-in-bio profesional dalam hitungan menit. Gabungkan Instagram, TikTok, YouTube, WhatsApp, dan semua tautanmu dalam satu link.'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-50 via-white to-white"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-200/50 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-40 -left-24 w-72 h-72 bg-indigo-200/50 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-5 sm:px-8 grid lg:grid-cols-2 gap-16 items-center">
            <div class="animate-fade-up">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-semibold">
                    ✨ Platform link-in-bio terbaik untuk kreator Indonesia
                </span>
                <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-ink-900 leading-[1.1]">
                    Satu link untuk<br>
                    <span class="bg-gradient-to-r from-brand-600 to-indigo-500 bg-clip-text text-transparent">semua kontenmu.</span>
                </h1>
                <p class="mt-6 text-lg text-ink-600 max-w-lg">
                    Kumpulkan semua tautan penting — Instagram, TikTok, YouTube, WhatsApp, toko online, dan lainnya — dalam satu halaman yang cantik dan mudah dibagikan.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo e(route('register')); ?>" class="px-7 py-3.5 rounded-full bg-brand-600 text-white font-semibold text-center shadow-soft hover:bg-brand-700 hover:-translate-y-0.5 transition-all">
                        Buat Halamanmu — Gratis
                    </a>
                    <a href="#how-it-works" class="px-7 py-3.5 rounded-full border-2 border-ink-200 text-ink-800 font-semibold text-center hover:border-ink-400 transition-all">
                        Lihat Cara Kerja
                    </a>
                </div>
                <div class="mt-8 flex items-center gap-6 text-sm text-ink-500">
                    <div class="flex items-center gap-1.5"><span class="text-brand-600">✓</span> Tanpa kartu kredit</div>
                    <div class="flex items-center gap-1.5"><span class="text-brand-600">✓</span> Setup 2 menit</div>
                </div>
            </div>

            
            <div class="flex justify-center lg:justify-end animate-fade-up" style="animation-delay:.15s">
                <div class="relative w-[280px] sm:w-[320px]">
                    <div class="rounded-[2.5rem] border-8 border-ink-900 bg-ink-900 shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-br from-brand-500 to-indigo-600 px-6 pt-10 pb-8 text-center">
                            <div class="w-20 h-20 rounded-full bg-white/20 border-4 border-white/40 mx-auto flex items-center justify-center text-3xl">🚀</div>
                            <p class="mt-3 font-bold text-white">@ nama.kamu</p>
                            <p class="text-white/80 text-xs mt-1">Content Creator ✨ Semua link di sini</p>
                        </div>
                        <div class="bg-white p-4 space-y-3">
                            <?php $__currentLoopData = ['📸 Instagram', '🎵 TikTok', '▶️ YouTube', '💬 WhatsApp', '🐙 GitHub']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="w-full py-3 rounded-full bg-ink-50 border border-ink-100 text-center text-sm font-semibold text-ink-700">
                                    <?php echo e($item); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="border-y border-ink-100 bg-ink-50/50 py-8">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 flex flex-wrap items-center justify-center gap-x-12 gap-y-4 text-ink-400 font-semibold text-sm">
            <span>Dipercaya oleh kreator, UMKM, dan profesional</span>
            <span class="hidden sm:inline">•</span>
            <span>Instagram</span><span>TikTok</span><span>YouTube</span><span>WhatsApp Business</span><span>Shopee</span>
        </div>
    </section>

    
    <section id="features" class="py-24">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-ink-900">Semua yang kamu butuhkan, dalam satu tempat</h2>
                <p class="mt-4 text-ink-600">Fitur lengkap untuk membangun kehadiran online kamu, tanpa perlu keahlian coding.</p>
            </div>

            <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                    $features = [
                        ['icon' => '🔗', 'title' => 'Link Tanpa Batas', 'desc' => 'Tambahkan sebanyak apapun tautan yang kamu mau — sosial media, toko, portofolio, dan lainnya.'],
                        ['icon' => '🎨', 'title' => 'Tema & Tampilan Kustom', 'desc' => 'Pilih tema warna, gaya tombol, dan background sesuai kepribadian brand kamu.'],
                        ['icon' => '🖱️', 'title' => 'Drag & Drop', 'desc' => 'Atur ulang urutan link hanya dengan menyeret dan melepas — real-time, tanpa reload.'],
                        ['icon' => '📊', 'title' => 'Analitik Mendalam', 'desc' => 'Pantau total kunjungan, klik per link, dan pengunjung unik dalam grafik yang mudah dibaca.'],
                        ['icon' => '📱', 'title' => 'Responsive Penuh', 'desc' => 'Halaman profilmu akan terlihat sempurna di semua perangkat, dari HP sampai desktop.'],
                        ['icon' => '⚡', 'title' => 'Real-time Preview', 'desc' => 'Lihat perubahan halamanmu secara langsung sebelum mempublikasikannya ke dunia.'],
                    ];
                ?>

                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-7 rounded-2xl border border-ink-100 bg-white hover:shadow-soft hover:-translate-y-1 transition-all duration-300">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-2xl"><?php echo e($feature['icon']); ?></div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900"><?php echo e($feature['title']); ?></h3>
                        <p class="mt-2 text-sm text-ink-600 leading-relaxed"><?php echo e($feature['desc']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section id="how-it-works" class="py-24 bg-ink-50/60">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-ink-900">Mulai dalam 3 langkah mudah</h2>
                <p class="mt-4 text-ink-600">Tidak perlu keahlian teknis. Halaman link-in-bio kamu siap dalam hitungan menit.</p>
            </div>

            <div class="mt-16 grid md:grid-cols-3 gap-8">
                <?php
                    $steps = [
                        ['num' => '1', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dengan email dan pilih username unik untuk URL halamanmu.'],
                        ['num' => '2', 'title' => 'Tambahkan Link & Kustomisasi', 'desc' => 'Masukkan semua tautanmu, atur urutan, pilih tema, dan unggah foto profil.'],
                        ['num' => '3', 'title' => 'Bagikan ke Dunia', 'desc' => 'Salin URL profilmu dan tempel di bio Instagram, TikTok, atau platform lainnya.'],
                    ];
                ?>
                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative p-8 rounded-2xl bg-white border border-ink-100 text-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-brand-500 to-indigo-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-soft">
                            <?php echo e($step['num']); ?>

                        </div>
                        <h3 class="mt-5 font-bold text-lg text-ink-900"><?php echo e($step['title']); ?></h3>
                        <p class="mt-2 text-sm text-ink-600"><?php echo e($step['desc']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    
    <section class="py-24">
        <div class="max-w-5xl mx-auto px-5 sm:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-indigo-700 px-8 py-16 sm:px-16 text-center shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Siap membangun halaman link-in-bio-mu?</h2>
                <p class="mt-4 text-brand-100 max-w-xl mx-auto">Bergabung sekarang dan dapatkan URL profil pribadimu, gratis selamanya.</p>
                <a href="<?php echo e(route('register')); ?>" class="mt-8 inline-block px-8 py-4 rounded-full bg-white text-brand-700 font-bold shadow-lg hover:-translate-y-0.5 hover:shadow-xl transition-all">
                    Buat Halaman Gratis Sekarang
                </a>
            </div>
        </div>
    </section>

    
    <section id="faq" class="py-24 bg-ink-50/60">
        <div class="max-w-3xl mx-auto px-5 sm:px-8">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-ink-900 text-center">Pertanyaan Umum</h2>

            <div class="mt-12 space-y-4" x-data="{ open: 0 }">
                <?php
                    $faqs = [
                        ['q' => 'Apakah TreeLink benar-benar gratis?', 'a' => 'Ya! Kamu bisa membuat akun dan halaman link-in-bio secara gratis dengan fitur lengkap: link tak terbatas, tema, dan analitik.'],
                        ['q' => 'Apakah saya bisa mengganti username setelah mendaftar?', 'a' => 'Bisa. Kamu dapat mengubah username kapan saja melalui menu Profil di dashboard, selama username tersebut belum digunakan orang lain.'],
                        ['q' => 'Bagaimana cara melihat statistik pengunjung?', 'a' => 'Buka menu Analytics di dashboard untuk melihat total kunjungan, klik per link, pengunjung unik, dan grafik tren 30 hari terakhir.'],
                        ['q' => 'Apakah halaman saya responsive di HP?', 'a' => 'Tentu. Setiap halaman profil dirancang mobile-first agar terlihat sempurna di semua ukuran layar.'],
                    ];
                ?>

                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-2xl border border-ink-100 bg-white overflow-hidden">
                        <button @click="open = open === <?php echo e($i); ?> ? -1 : <?php echo e($i); ?>" class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-ink-900">
                            <?php echo e($faq['q']); ?>

                            <span x-text="open === <?php echo e($i); ?> ? '−' : '+'" class="text-brand-600 text-xl flex-shrink-0"></span>
                        </button>
                        <div x-show="open === <?php echo e($i); ?>" x-collapse x-cloak class="px-6 pb-5 text-sm text-ink-600 leading-relaxed">
                            <?php echo e($faq['a']); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\treelink\linkbio\resources\views/welcome.blade.php ENDPATH**/ ?>