<?php

namespace App\Http\Controllers\admin;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Models\Categories;

class AdminCategoryController extends Controller
{
    public function list()
    {
        $categories = Categories::where('status', '!=', CategoryStatus::DELETED)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.categories.list', compact('categories'));
    }

    public function detail($id)
    {
        $category = Categories::find($id);
        if (!$category || $category->status == CategoryStatus::DELETED) {
            return view('error.404');
        }

        $categories = Categories::where('status', '!=', CategoryStatus::DELETED)
            ->where('parent_id', null)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.categories.detail', compact('category', 'categories'));
    }

    public function create()
    {
        $categories = Categories::where('status', '!=', CategoryStatus::DELETED)
            ->where('parent_id', null)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }
}
