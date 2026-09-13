@extends('layouts.app')

@section('content')
<div x-data="{ tab: 'expense', openMenu: null }">
    <div class="bg-white border-b border-gray-100 px-5 py-4">
        <h1 class="text-lg font-semibold text-gray-800">Categories</h1>
    </div>

    <div class="px-5 pt-4 pb-2">
        <div class="flex bg-gray-100 rounded-xl p-1 gap-3">
            <button type="button" @click="tab = 'expense'"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="tab === 'expense' ? 'bg-red-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                Expense ({{ $expenseCategories->count() }})
            </button>
            <button type="button" @click="tab = 'income'"
                    class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                    :class="tab === 'income' ? 'bg-emerald-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                Income ({{ $incomeCategories->count() }})
            </button>
        </div>
    </div>

    <div class="px-5 pt-2 pb-24">
        <div x-show="tab === 'expense'" x-cloak>
            @forelse($expenseCategories as $category)
                @include('app.categories.partials.category-row', ['category' => $category])
            @empty
                <div class="text-center py-12 text-gray-400">
                    <p class="font-medium">No expense categories yet</p>
                    <p class="text-sm mt-1">Tap + to create one</p>
                </div>
            @endforelse
        </div>

        <div x-show="tab === 'income'" x-cloak>
            @forelse($incomeCategories as $category)
                @include('app.categories.partials.category-row', ['category' => $category])
            @empty
                <div class="text-center py-12 text-gray-400">
                    <p class="font-medium">No income categories yet</p>
                    <p class="text-sm mt-1">Tap + to create one</p>
                </div>
            @endforelse
        </div>
    </div>

    <a href="{{ route('categories.create') }}"
       class="fixed bottom-24 right-5 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all z-40">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </a>
</div>
@endsection
