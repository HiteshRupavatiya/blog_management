<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ListingApiTrait;

    public function list(Request $request)
    {
        $this->ListingValidation();
        $query = Category::query();

        $searchableFields = ['name'];

        $data = $this->filterSearchPagination($query, $searchableFields);

        return ok('Categories fetched successfully', [
            'categories' => $data['query']->get(),
            'count'      => $data['count']
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:15|unique:categories,name'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return ok('Category created successfully', $category);
    }

    public function get($id)
    {
        $category = Category::find($id);

        if ($category) {
            return ok('Category fetched successfully', $category);
        }

        return error('Category not found', type: 'notfound');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:15|unique:categories,name'
        ]);

        $category = Category::find($id);

        if ($category) {
            $category->update([
                'name' => $request->name
            ]);
            return ok('Category updated successfully');
        }

        return error('Category not found', type: 'notfound');
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return ok('Category deleted successfully');
        }

        return error('Category not found', type: 'notfound');
    }
}
