<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('Admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('Admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:categories,code',
            'name' => 'required',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return view('Admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'code' => 'required|unique:categories,code,' . $category->id,
            'name' => 'required',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
