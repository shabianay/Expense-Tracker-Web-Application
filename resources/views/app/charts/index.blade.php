@extends('layouts.app')

@section('content')
    <div x-data="chartsPage()" x-init="init()">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-100 px-5 py-3 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">Charts</h1>
            <div class="flex items-center gap-2">
                <button @click="prevMonth()" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <select x-model="month" @change="window.location.href='?month='+month+'&type='+chartType" class="text-sm border border-gray-300 rounded-lg px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-36">
                    @foreach(range(date('Y') - 2, date('Y')) as $y)
                        @foreach(range(1, 12) as $m)
                            @php
                                $value = "$y-".str_pad($m, 2, '0', STR_PAD_LEFT);
                                $label = date('F Y', mktime(0, 0, 0, $m, 1, $y));
                            @endphp
                            <option value="{{ $value }}" @if($month === $value) selected @endif>{{ $label }}</option>
                        @endforeach
                    @endforeach
                </select>
                <button @click="nextMonth()" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="px-5 pt-5 grid grid-cols-2 gap-3">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <div class="text-gray-500 text-xs mb-1">Expense</div>
                <div class="text-red-500 font-bold text-lg">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <div class="text-gray-500 text-xs mb-1">Income</div>
                <div class="text-emerald-500 font-bold text-lg">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="px-5 pt-4">
            <div class="flex bg-gray-100 rounded-xl p-1 gap-4">
                <button @click="switchType('expense')"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="chartType === 'expense' ? 'bg-red-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                    Expense
                </button>
                <button @click="switchType('income')"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="chartType === 'income' ? 'bg-emerald-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                    Income
                </button>
            </div>
        </div>

        {{-- Chart --}}
        <div class="px-5 pt-5">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <canvas id="donutChart"></canvas>
            </div>
        </div>

        {{-- Categories --}}
        <div class="px-5 pt-5 pb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-3">
            @foreach ($categoryBreakdown as $cat)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs"
                        style="background-color: {{ $cat['color'] }}">@include('components.category-icon', ['icon' => $cat['icon'] ?? 'circle'])</div>
                    <div class="flex-1">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 font-medium">{{ $cat['name'] }}</span>
                            <span class="text-gray-500 font-bold">Rp {{ number_format($cat['amount'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full"
                                style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>

    <script>
        function chartsPage() {
            return {
                month: '{{ $month }}',
                monthLabel: '',
                chartType: '{{ $type ?? 'expense' }}',
                chart: null,

                init() {
                    this.updateLabel();
                    this.renderChart();
                },

                updateLabel() {
                    const [y, m] = this.month.split('-');
                    const date = new Date(y, m - 1);
                    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                        'October', 'November', 'December'
                    ];
                    this.monthLabel = months[date.getMonth()] + ' ' + date.getFullYear();
                },

                switchType(type) {
                    window.location.href = '?month=' + this.month + '&type=' + type;
                },

                prevMonth() {
                    const [y, m] = this.month.split('-').map(Number);
                    const d = new Date(y, m - 2, 1);
                    const newMonth = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                    window.location.href = '?month=' + newMonth + '&type=' + this.chartType;
                },

                nextMonth() {
                    const [y, m] = this.month.split('-').map(Number);
                    const d = new Date(y, m, 1);
                    const newMonth = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                    window.location.href = '?month=' + newMonth + '&type=' + this.chartType;
                },

                renderChart() {
                    const existing = Chart.getChart('donutChart');
                    if (existing) {
                        existing.destroy();
                    }
                    const canvas = document.getElementById('donutChart');
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode(array_column($categoryBreakdown, 'name')) !!},
                            datasets: [{
                                data: {!! json_encode(array_column($categoryBreakdown, 'amount')) !!},
                                backgroundColor: {!! json_encode(array_column($categoryBreakdown, 'color')) !!},
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            cutout: '70%'
                        }
                    });
                }
            }
        }
    </script>
@endsection
