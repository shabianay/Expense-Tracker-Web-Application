@extends('layouts.app')

@section('content')
    <div x-data="transactionForm()" x-init="init()">
        {{-- Header --}}
        <div class="bg-white border-b border-gray-100 px-5 py-4 flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-semibold text-gray-800">Edit Transaction</h1>
        </div>

        {{-- Type Toggle --}}
        <div class="px-5 pt-4">
            <div class="flex rounded-xl p-1 gap-4">
                <button @click="type = 'expense'" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="type === 'expense' ? 'bg-red-500 text-white shadow border-transparent' :
                        'text-gray-500 border-gray-300 bg-white'">
                    Expense
                </button>
                <button @click="type = 'income'" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="type === 'income' ? 'bg-emerald-500 text-white shadow border-transparent' :
                        'text-gray-500 border-gray-300 bg-white'">
                    Income
                </button>
            </div>
        </div>

        {{-- Amount Display --}}
        <div class="px-5 pt-6 pb-2 text-center">
            <div class="text-4xl font-bold tracking-tight 
            text-gray-800"
                x-text="'Rp ' + formatNumber(displayAmount)">
            </div>
        </div>

        {{-- Category Selector --}}
        <div class="px-5 pt-4 pb-3">
            <div class="text-sm text-gray-500 mb-2 font-medium">Category</div>
            <div class="grid grid-cols-5 gap-2">
                @foreach ($categories as $category)
                    <button type="button" @click="categoryId = {{ $category->id }}"
                        class="flex flex-col items-center gap-1 p-2 rounded-xl transition-all"
                        :class="categoryId === {{ $category->id }} ? 'bg-blue-50 ring-2 ring-blue-500' :
                            'bg-white hover:bg-white'"
                        x-show="type === '{{ $category->type }}'">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg"
                            style="background-color: {{ $category->color }}">
                            @include('components.category-icon', ['icon' => $category->icon])
                        </div>
                        <span class="text-xs text-gray-600 truncate w-full text-center">{{ $category->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Note Input --}}
        <div class="px-5 pt-2">
            <input type="text" x-model="note" placeholder="Add a note..."
                class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        {{-- Date/Time Picker --}}
        <div class="px-5 pt-3 flex items-center gap-3">
            <div class="flex-1">
                <input type="date" x-model="date"
                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <input type="time" x-model="time"
                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        {{-- Custom Numeric Keypad --}}
        <div class="px-5 pt-6 pb-4">
            <div class="grid grid-cols-3 gap-3 sm:gap-4">
                <button @click="pressKey('1')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">1</button>
                <button @click="pressKey('2')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">2</button>
                <button @click="pressKey('3')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">3</button>
                <button @click="pressKey('4')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">4</button>
                <button @click="pressKey('5')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">5</button>
                <button @click="pressKey('6')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">6</button>
                <button @click="pressKey('7')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">7</button>
                <button @click="pressKey('8')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">8</button>
                <button @click="pressKey('9')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">9</button>
                <button @click="pressKey('0')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-xl sm:text-2xl font-semibold text-gray-700 transition-colors min-h-[52px]">0</button>
                <button @click="pressKey('backspace')"
                    class="py-4 sm:py-5 bg-white hover:bg-white rounded-xl text-gray-500 transition-colors min-h-[52px] flex items-center justify-center col-span-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-3">
                <button @click="deleteTransaction()"
                    class="py-4 sm:py-5 bg-red-100 hover:bg-red-50 rounded-xl text-sm font-semibold text-red-500 transition-colors min-h-[52px]">
                    Delete
                </button>
                <button @click="submitForm()"
                    class="py-4 sm:py-5 rounded-xl text-sm font-semibold transition-colors min-h-[52px]"
                    :class="type === 'income' ? 'bg-emerald-100 hover:bg-emerald-50 text-emerald-600' :
                        'bg-red-100 hover:bg-red-50 text-red-600'">
                    Save
                </button>
            </div>
        </div>

        {{-- Hidden Form --}}
        <form x-ref="transactionForm" method="POST" action="{{ route('transactions.update', $transaction) }}"
            class="hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" :value="type">
            <input type="hidden" name="category_id" :value="categoryId">
            <input type="hidden" name="amount" :value="displayAmount">
            <input type="hidden" name="note" :value="note">
            <input type="hidden" name="date" :value="date + ' ' + time + ':00'">
        </form>

        {{-- Delete Form --}}
        <form x-ref="deleteForm" method="POST" action="{{ route('transactions.destroy', $transaction) }}"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function transactionForm() {
            return {
                type: '{{ $transaction->type }}',
                categoryId: {{ $transaction->category_id }},
                note: @json($transaction->note),
                amountStr: '{{ $transaction->amount }}',
                date: '{{ $transaction->transaction_date->format('Y-m-d') }}',
                time: '{{ $transaction->transaction_date->format('H:i') }}',

                init() {},

                get displayAmount() {
                    try {
                        let expr = this.amountStr;
                        if (expr.includes('+') || expr.includes('-')) {
                            let result = 0;
                            let current = '';
                            let op = '+';
                            for (let i = 0; i < expr.length; i++) {
                                if (expr[i] === '+' || expr[i] === '-') {
                                    result = op === '+' ? result + parseFloat(current || 0) : result - parseFloat(
                                        current || 0);
                                    op = expr[i];
                                    current = '';
                                } else {
                                    current += expr[i];
                                }
                            }
                            result = op === '+' ? result + parseFloat(current || 0) : result - parseFloat(current || 0);
                            return Math.max(0, Math.round(result));
                        }
                        return Math.max(0, parseFloat(this.amountStr) || 0);
                    } catch {
                        return 0;
                    }
                },

                pressKey(key) {
                    if (key === 'backspace') {
                        if (this.amountStr.length > 1) {
                            this.amountStr = this.amountStr.slice(0, -1);
                        } else {
                            this.amountStr = '0';
                        }
                    } else if (key === '+' || key === '-') {
                        if (this.amountStr !== '0') {
                            this.amountStr += key;
                        }
                    } else {
                        if (this.amountStr === '0') {
                            this.amountStr = key;
                        } else {
                            this.amountStr += key;
                        }
                    }
                },

                formatNumber(n) {
                    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },

                submitForm() {
                    if (this.displayAmount <= 0) return;
                    if (!this.categoryId) {
                        alert('Please select a category');
                        return;
                    }
                    this.$refs.transactionForm.submit();
                },

                deleteTransaction() {
                    if (confirm('Delete this transaction?')) {
                        this.$refs.deleteForm.submit();
                    }
                }
            }
        }
    </script>
@endsection
