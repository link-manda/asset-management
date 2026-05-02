<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::withCount('assets')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'default_useful_life_months' => 'nullable|integer|min:1',
            'default_residual_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu kategori.
     */
    public function show(Category $category)
    {
        $category->load('assets');
        return view('categories.show', compact('category'));
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'default_useful_life_months' => 'nullable|integer|min:1',
            'default_residual_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        if ($category->assets()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kategori ini masih memiliki aset yang terdaftar.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
