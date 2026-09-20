@extends('layouts.dashboard')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $cards = [
                ['label' => 'Total Kunjungan', 'value' => number_format($totalViews), 'icon' => '👁️'],
                ['label' => 'Total Klik', 'value' => number_format($totalClicks), 'icon' => '🖱️'],
                ['label' => 'Pengunjung Unik', 'value' => number_format($uniqueVisitors), 'icon' => '🧑‍🤝‍🧑'],
                ['label' => 'Click-Through Rate', 'value' => $ctr.'%', 'icon' => '🎯'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-lg">{{ $card['icon'] }}</div>
                <p class="mt-4 text-2xl font-extrabold text-ink-900">{{ $card['value'] }}</p>
                <p class="text-sm text-ink-500">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card mb-8">
        <h2 class="font-bold text-ink-900 mb-1">Tren 30 Hari Terakhir</h2>
        <p class="text-sm text-ink-500 mb-5">Kunjungan halaman dan klik link dari waktu ke waktu.</p>
        <div class="h-72">
            <canvas id="analytics-chart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
        <h2 class="font-bold text-ink-900 mb-5">Statistik Per Link</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-ink-400 border-b border-ink-100">
                        <th class="pb-3 font-semibold">Link</th>
                        <th class="pb-3 font-semibold">URL</th>
                        <th class="pb-3 font-semibold text-right">Klik</th>
                        <th class="pb-3 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($linkStats as $link)
                        <tr class="border-b border-ink-50 last:border-0">
                            <td class="py-3 font-semibold text-ink-800">{{ $link->title }}</td>
                            <td class="py-3 text-ink-500 max-w-xs truncate">{{ $link->url }}</td>
                            <td class="py-3 text-right font-bold text-ink-900">{{ number_format($link->clicks_count) }}</td>
                            <td class="py-3 text-right">
                                @if ($link->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-ink-100 text-ink-500 text-xs font-semibold">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-ink-400">Belum ada data link.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.3/chart.umd.min.js"></script>
<script>
    fetch('{{ route('dashboard.analytics.chart-data') }}')
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('analytics-chart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Kunjungan',
                            data: data.views,
                            borderColor: '#7c4dff',
                            backgroundColor: 'rgba(124,77,255,0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 0,
                        },
                        {
                            label: 'Klik',
                            data: data.clicks,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.08)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f1f1f6' } },
                    },
                },
            });
        });
</script>
@endsection
