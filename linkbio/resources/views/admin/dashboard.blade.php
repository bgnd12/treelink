@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
    <!-- Welcome Banner -->
    <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-900 tracking-tight">Selamat datang kembali, Super Admin</h1>
            <p class="text-ink-500 font-medium mt-1">Berikut adalah ringkasan performa sistem TreeLink hari ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users') }}" class="px-5 py-2.5 rounded-full bg-white border border-ink-200 text-sm font-bold text-ink-700 hover:border-ink-300 hover:shadow-sm transition">
                Kelola User
            </a>
            <button class="px-5 py-2.5 rounded-full bg-brand-600 text-white text-sm font-bold shadow-lg shadow-brand-500/30 hover:bg-brand-700 hover:shadow-xl transition-all">
                Export Data
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            // Calculate mock trend percentages for UI purposes if real trend data isn't easily available
            $trends = [
                'users' => ['val' => '+12%', 'up' => true],
                'links' => ['val' => '+24%', 'up' => true],
                'views' => ['val' => '+8.5%', 'up' => true],
                'active' => ['val' => '-2.1%', 'up' => false],
            ];
            
            $cards = [
                ['id' => 'users', 'label' => 'Total User', 'value' => number_format($stats['total_users']), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />', 'color' => 'brand', 'bg' => 'bg-brand-50', 'text' => 'text-brand-600'],
                ['id' => 'links', 'label' => 'Total Links', 'value' => number_format($stats['total_links']), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />', 'color' => 'indigo', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
                ['id' => 'views', 'label' => 'Total Kunjungan', 'value' => number_format($stats['total_views']), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />', 'color' => 'sky', 'bg' => 'bg-sky-50', 'text' => 'text-sky-600'],
                ['id' => 'active', 'label' => 'User Aktif', 'value' => number_format($stats['active_users']), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />', 'color' => 'emerald', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="bg-white rounded-[20px] p-6 shadow-sm border border-ink-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl {{ $card['bg'] }} {{ $card['text'] }} flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $card['icon'] !!}
                        </svg>
                    </div>
                    
                    @php $t = $trends[$card['id']]; @endphp
                    <div class="flex items-center gap-1 {{ $t['up'] ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' }} px-2 py-1 rounded-lg text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            @if($t['up'])
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            @else
                                <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                            @endif
                        </svg>
                        {{ $t['val'] }}
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-ink-900 tracking-tight">{{ $card['value'] }}</h3>
                    <p class="text-sm font-semibold text-ink-500 mt-1">{{ $card['label'] }}</p>
                </div>
                
                <!-- Decorative background blob -->
                <div class="absolute -bottom-6 -right-6 w-24 h-24 {{ $card['bg'] }} rounded-full opacity-50 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        @endforeach
    </div>

    <!-- Content Grid -->
    <div class="mt-8 grid lg:grid-cols-3 gap-8">
        
        <!-- Left Column (Chart & Latest Users) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Chart Card -->
            <div class="bg-white rounded-[20px] p-6 shadow-sm border border-ink-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-ink-900">Tren Kunjungan</h2>
                        <p class="text-sm text-ink-500">Statistik pengunjung profil bulan ini</p>
                    </div>
                    <select class="bg-[#F7F7FA] border-0 rounded-xl text-sm font-semibold text-ink-700 py-2 pl-4 pr-8 focus:ring-2 focus:ring-brand-500/20 cursor-pointer outline-none">
                        <option>Bulan Ini</option>
                        <option>Bulan Lalu</option>
                        <option>Tahun Ini</option>
                    </select>
                </div>
                <div class="h-[280px] w-full">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>

            <!-- Latest Users Table -->
            <div class="bg-white rounded-[20px] p-0 shadow-sm border border-ink-100 overflow-hidden">
                <div class="p-6 border-b border-ink-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-ink-900">User Terbaru</h2>
                        <p class="text-sm text-ink-500">Pengguna yang baru saja mendaftar</p>
                    </div>
                    <a href="{{ route('admin.users') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 transition">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-ink-50/50 text-ink-500 font-semibold">
                            <tr>
                                <th class="px-6 py-4">Pengguna</th>
                                <th class="px-6 py-4 text-center">Links</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink-50">
                            @foreach ($latestUsers->take(5) as $u)
                            <tr class="hover:bg-ink-50/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random" class="w-10 h-10 rounded-xl" alt="{{ $u->name }}">
                                        <div>
                                            <p class="font-bold text-ink-900">{{ $u->name }}</p>
                                            <a href="{{ $u->publicUrl() }}" target="_blank" class="text-xs text-brand-600 hover:underline">/{{ $u->username }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-ink-700">{{ $u->links_count }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold {{ $u->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.show', $u) }}" class="inline-block p-2 text-ink-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Sidebar (Most Viewed & Summary) -->
        <div class="space-y-8">
            
            <!-- System Summary -->
            <div class="bg-gradient-to-br from-[#0A0B1A] to-[#1a1b3a] rounded-[20px] p-6 shadow-xl text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-brand-500/20 blur-3xl"></div>
                
                <h3 class="text-lg font-bold mb-5 flex items-center gap-2">
                    <span class="text-brand-400">⚡</span> Ringkasan Sistem
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/10">
                        <span class="text-sm font-medium text-ink-300">Versi PHP</span>
                        <span class="text-sm font-bold text-white">{{ phpversion() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/10">
                        <span class="text-sm font-medium text-ink-300">Laravel</span>
                        <span class="text-sm font-bold text-white">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/10">
                        <span class="text-sm font-medium text-ink-300">Database Size</span>
                        <span class="text-sm font-bold text-emerald-400">Normal</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-white/10">
                    <a href="#" class="block w-full py-3 text-center rounded-xl bg-white text-[#0A0B1A] text-sm font-bold hover:bg-ink-100 transition">
                        System Settings
                    </a>
                </div>
            </div>

            <!-- Most Viewed Users -->
            <div class="bg-white rounded-[20px] p-6 shadow-sm border border-ink-100">
                <h2 class="text-lg font-bold text-ink-900 mb-6">Paling Banyak Dikunjungi</h2>
                <div class="space-y-5">
                    @forelse ($mostViewedUsers as $index => $u)
                        <div class="flex items-center gap-4">
                            <div class="w-8 text-center text-sm font-extrabold {{ $index === 0 ? 'text-amber-500' : ($index === 1 ? 'text-slate-400' : ($index === 2 ? 'text-amber-700' : 'text-ink-300')) }}">
                                #{{ $index + 1 }}
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random" class="w-10 h-10 rounded-full" alt="Avatar">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-ink-900 truncate">{{ $u->name }}</p>
                                <p class="text-xs font-medium text-ink-400 truncate">/{{ $u->username }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="block text-sm font-extrabold text-ink-900">{{ number_format($u->profile_views_count) }}</span>
                                <span class="block text-[10px] uppercase font-bold text-ink-400">Views</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-400 text-center py-4">Belum ada data kunjungan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Promo -->
            <div class="bg-brand-50 rounded-[20px] p-6 border border-brand-100 text-center relative overflow-hidden">
                <div class="absolute inset-0 pattern-dots opacity-50"></div>
                <div class="relative z-10">
                    <span class="inline-block p-3 bg-white rounded-2xl shadow-sm text-2xl mb-4">✨</span>
                    <h4 class="font-extrabold text-ink-900 mb-2">TreeLink Pro</h4>
                    <p class="text-sm text-ink-600 mb-4 font-medium">Buka lebih banyak fitur premium untuk pengguna Anda.</p>
                    <a href="#" class="inline-block px-6 py-2.5 bg-ink-900 text-white rounded-xl text-sm font-bold shadow-md hover:bg-ink-800 transition">
                        Upgrade Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('viewsChart').getContext('2d');
            
            // Gradient for chart area
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(124, 77, 255, 0.2)');   // brand-500 with opacity
            gradient.addColorStop(1, 'rgba(124, 77, 255, 0)');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Kunjungan',
                        data: [120, 190, 150, 220, 180, 290, 310], // Dummy data representing the trend
                        borderColor: '#7c4dff', // brand-500
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#7c4dff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4 // curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0e0e17',
                            padding: 12,
                            titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                            bodyFont: { size: 13, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                            displayColors: false,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }, color: '#8d8daf' }
                        },
                        y: {
                            grid: { color: '#f7f7fb', drawBorder: false },
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }, color: '#8d8daf', padding: 10 }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });
    </script>
@endsection
