<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $userId = $this->getUserId();
        $expenseCategories = Category::where('user_id', $userId)->where('type', 'expense')->withCount('transactions')->orderBy('sort_order')->get();
        $incomeCategories = Category::where('user_id', $userId)->where('type', 'income')->withCount('transactions')->orderBy('sort_order')->get();

        return view('app.categories.index', compact('expenseCategories', 'incomeCategories'));
    }

    public function show(Category $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function create()
    {
        return view('app.categories.create');
    }

    public function store(Request $request)
    {
        $userId = $this->getUserId();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $maxOrder = Category::where('user_id', $userId)->where('type', $validated['type'])->max('sort_order');

        Category::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'icon' => $validated['icon'] ?? 'circle',
            'color' => $validated['color'] ?? '#4D96FF',
            'sort_order' => ($maxOrder ?? 0) + 1,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($category->user_id === $this->getUserId(), 403);

        return view('app.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($category->user_id === $this->getUserId(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        $category->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'icon' => $validated['icon'] ?? 'circle',
            'color' => $validated['color'] ?? '#4D96FF',
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($category->user_id === $this->getUserId(), 403);

        $userId = $this->getUserId();
        $uncategorized = Category::firstOrCreate(
            ['user_id' => $userId, 'name' => 'Uncategorized', 'type' => $category->type],
            ['icon' => 'circle', 'color' => '#9CA3AF', 'sort_order' => 999]
        );

        Transaction::where('category_id', $category->id)->update(['category_id' => $uncategorized->id]);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:categories,id',
        ]);

        foreach ($validated['ids'] as $index => $id) {
            Category::where('user_id', $this->getUserId())->where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
