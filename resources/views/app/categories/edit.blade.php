@extends('layouts.app')

@section('content')
<div x-data="categoryForm()" x-init="init()">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-semibold text-gray-800">Edit Category</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="px-5 pt-5">
            <label class="block text-sm font-medium text-gray-600 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   placeholder="Category name"
                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Type --}}
        <div class="px-5 pt-4">
            <label class="block text-sm font-medium text-gray-600 mb-2">Type</label>
            <div class="flex bg-white-100 rounded-xl p-1 gap-4">
                <button type="button" @click="type = 'expense'"
                        class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                        :class="type === 'expense' ? 'bg-red-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                    Expense
                </button>
                <button type="button" @click="type = 'income'"
                        class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all border"
                        :class="type === 'income' ? 'bg-emerald-500 text-white shadow border-transparent' : 'text-gray-500 border-gray-300 bg-white'">
                    Income
                </button>
            </div>
            <input type="hidden" name="type" :value="type">
        </div>

        {{-- Color Picker --}}
        <div class="px-5 pt-4">
            <label class="block text-sm font-medium text-gray-600 mb-2">Color</label>
            <div class="flex flex-wrap gap-2">
                @foreach(['#ef4444','#f97316','#eab308','#22c55e','#14b8a6','#06b6d4','#3b82f6','#6366f1','#8b5cf6','#ec4899','#f43f5e','#78716c'] as $color)
                <button type="button" @click="color = '{{ $color }}'"
                        class="w-9 h-9 rounded-full border-2 transition-all"
                        :class="color === '{{ $color }}' ? 'border-gray-800 scale-110' : 'border-transparent hover:scale-105'"
                        style="background-color: {{ $color }}">
                </button>
                @endforeach
            </div>
            <div class="flex items-center gap-3 mt-3">
                <input type="color" x-model="color" aria-label="Custom category color" class="w-12 h-10 rounded-lg border-0 p-1 cursor-pointer">
                <input type="text" name="color" x-model="color" maxlength="20"
                       placeholder="#3b82f6"
                       class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        {{-- Icon Selector --}}
        <div class="px-5 pt-4 pb-4">
            <label class="block text-sm font-medium text-gray-600 mb-2">Icon</label>
            <div class="grid grid-cols-8 gap-2">
                @foreach(['🍔','🛒','🚗','🏠','🎮','💊','📚','✈️','☕','🎬','💰','🎁','📱','💻','🎓','💊','🏥','🚌','🎵','🐾','👶','💼','🔧','💡','📱','💳','🏦','📊','🎯','🔖'] as $icon)
                <button type="button" @click="icon = '{{ $icon }}'"
                        class="w-10 h-10 rounded-xl flex items-center justify-center text-xl transition-all"
                        :class="icon === '{{ $icon }}' ? 'bg-blue-100 ring-2 ring-blue-500' : 'bg-white hover:bg-white-100'">
                    {{ $icon }}
                </button>
                @endforeach
            </div>
            <div class="flex items-center gap-3 mt-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl text-white shrink-0" :style="`background-color: ${color}`" x-text="icon || '🔖'"></div>
                <input type="text" name="icon" x-model="icon" maxlength="20"
                       placeholder="Custom icon or emoji"
                       class="flex-1 bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        {{-- Submit --}}
        <div class="px-5 pt-2 pb-6">
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-xl transition-colors">
                Update Category
            </button>
        </div>
    </form>
</div>

<script>
function categoryForm() {
    return {
        type: '{{ $category->type }}',
        color: @json($category->color),
        icon: @json($category->icon),

        init() {}
    }
}
</script>
@endsection