@extends('layouts.app')

@section('content')
    <div x-data="dashboard()" x-init="init()">
        {{-- Blue Gradient Header with Wave --}}
        <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 pt-5 pb-8 px-5">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-white font-semibold text-lg">Dashboard</h1>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth()" class="text-white/80 hover:text-white p-1 rounded hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <select x-model="month" @change="window.location.href='?month='+month" class="text-sm border border-white/30 rounded-lg px-3 py-1 text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 w-36">
                        @foreach(range(date('Y') - 2, date('Y')) as $y)
                            @foreach(range(1, 12) as $m)
                                @php
                                    $value = "$y-".str_pad($m, 2, '0', STR_PAD_LEFT);
                                    $label = date('F Y', mktime(0, 0, 0, $m, 1, $y));
                                @endphp
                                <option value="{{ $value }}" @if($month === $value) selected @endif class="text-gray-800 bg-white">{{ $label }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <button @click="nextMonth()" class="text-white/80 hover:text-white p-1 rounded hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button @click="showBalance = !showBalance" class="text-white/80 hover:text-white p-1 rounded hover:bg-white/10 ml-1">
                        <template x-if="showBalance">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </template>
                        <template x-if="!showBalance">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="space-y-3">
                {{-- Balance --}}
                <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-4">
                    <div class="text-white/70 text-sm mb-1">Balance</div>
                    <div class="text-white text-3xl font-bold tracking-tight"
                        x-text="showBalance ? formatCurrency({{ $balance }}) : '••••••••'"></div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Income --}}
                    <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-4">
                        <div class="text-white/70 text-sm mb-1">Income</div>
                        <div class="text-white text-xl font-bold"
                            x-text="showBalance ? formatCurrency({{ $totalIncome }}) : '•••••'"></div>
                    </div>
                    {{-- Expense --}}
                    <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-4">
                        <div class="text-white/70 text-sm mb-1">Expense</div>
                        <div class="text-white text-xl font-bold"
                            x-text="showBalance ? formatCurrency({{ $totalExpense }}) : '•••••'"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction List --}}
        <div class="px-5 mt-5 pb-8">
            <h2 class="text-gray-800 font-bold text-lg mb-3">Transactions</h2>

            @forelse($transactions as $group)
                <div class="mb-4">
                    {{-- Date Group Header --}}
                    <div class="flex justify-between items-center mb-2">
                        <span
                            class="text-gray-500 text-sm font-medium">{{ \Carbon\Carbon::parse($group['date'])->format('d M Y') }}</span>
                        <div class="flex gap-3 text-xs font-bold">
                            @if($group['dayIncome'] > 0)
                                <span class="text-emerald-500">
                                    +{{ number_format($group['dayIncome'], 0, ',', '.') }}
                                </span>
                            @endif
                            @if($group['dayExpense'] > 0)
                                <span class="text-red-500">
                                    -{{ number_format($group['dayExpense'], 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Transaction Rows --}}
                    @foreach ($group['transactions'] as $transaction)
                        <a href="{{ route('transactions.edit', $transaction) }}"
                            class="flex items-center gap-3 bg-white rounded-xl p-3 mb-2 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg font-bold shrink-0"
                                style="background-color: {{ $transaction->category->color ?? '#6b7280' }}">
                                @include('components.category-icon', [
                                    'icon' => $transaction->category->icon ?? 'circle',
                                ])
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-gray-800 font-medium text-sm">
                                    {{ $transaction->category->name ?? 'Uncategorized' }}</div>
                                <div class="text-gray-400 text-xs truncate">{{ $transaction->note }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <div
                                    class="font-bold text-sm {{ $transaction->type === 'income' ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-medium">No transactions yet</p>
                    <p class="text-sm mt-1">Tap + to add your first transaction</p>
                </div>
            @endforelse

        </div>

        {{-- FAB Button --}}
        <a href="{{ route('transactions.create') }}"
            class="fixed bottom-24 right-5 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all z-40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </a>

        {{-- Export Button --}}
        <a href="{{ route('export', ['month' => $month]) }}"
            class="fixed bottom-24 left-5 w-12 h-12 bg-white hover:bg-gray-50 text-gray-600 rounded-full shadow-lg flex items-center justify-center transition-all z-40 border border-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
        </a>
    </div>

    <script>
        function dashboard() {
            return {
                showBalance: true,
                month: '{{ $month }}',
                monthLabel: '',

                init() {
                    this.updateLabel();
                },

                updateLabel() {
                    const [y, m] = this.month.split('-');
                    const date = new Date(y, m - 1);
                    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                        'October', 'November', 'December'
                    ];
                    this.monthLabel = months[date.getMonth()] + ' ' + date.getFullYear();
                },

                prevMonth() {
                    const [y, m] = this.month.split('-').map(Number);
                    const d = new Date(y, m - 2, 1);
                    this.month = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                    window.location.href = '?month=' + this.month;
                },

                nextMonth() {
                    const [y, m] = this.month.split('-').map(Number);
                    const d = new Date(y, m, 1);
                    this.month = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                    window.location.href = '?month=' + this.month;
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0,
                    }).format(amount);
                }
            }
        }
    </script>
@endsection
