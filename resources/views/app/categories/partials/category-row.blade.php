<div x-data="{ showDeleteModal: false }" class="flex items-center justify-between bg-white rounded-xl p-3 mb-2 shadow-sm">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg shrink-0"
            style="background-color: {{ $category->color ?? '#4D96FF' }}">
            @include('components.category-icon', ['icon' => $category->icon])
        </div>
        <div class="min-w-0">
            <div class="text-gray-800 font-medium truncate">{{ $category->name }}</div>
            <div class="text-gray-400 text-xs">{{ $category->transactions_count ?? 0 }} transactions</div>
        </div>
    </div>

    <div class="relative shrink-0">
        <button type="button" @click="openMenu = openMenu === {{ $category->id }} ? null : {{ $category->id }}"
            class="text-gray-800 hover:text-gray-600 p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01" />
            </svg>
        </button>
        <div x-show="openMenu === {{ $category->id }}" x-cloak @click.outside="openMenu = null"
            class="absolute right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-gray-100 py-1 min-w-[140px] z-20">
            <a href="{{ route('categories.edit', $category) }}"
                class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50">Edit</a>
            <button type="button" @click="openMenu = null; showDeleteModal = true"
                class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-50">Delete</button>
        </div>
    </div>

    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showDeleteModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                            Hapus Kategori
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus kategori <span
                                    class="font-semibold text-gray-700">"{{ $category->name }}"</span>? Tindakan ini
                                tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false"
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
