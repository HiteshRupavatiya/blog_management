<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ListingApiTrait;

    public function list(Request $request)
    {
        $this->ListingValidation();
        $query = Tag::query();

        $searchableFields = ['name'];

        $data = $this->filterSearchPagination($query, $searchableFields);

        return ok('Tags fetched successfully', [
            'tags'  => $data['query']->get(),
            'count' => $data['count']
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:15|unique:tags,name'
        ]);

        $tag = Tag::create([
            'name' => $request->name
        ]);

        return ok('Tag created successfully', $tag);
    }

    public function get($id)
    {
        $tag = Tag::find($id);

        if ($tag) {
            return ok('Tag fetched successfully', $tag);
        }

        return error('Tag not found', type: 'notfound');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:4|max:15|unique:tags,name'
        ]);

        $tag = Tag::find($id);

        if ($tag) {
            $tag->update([
                'name' => $request->name
            ]);
            return ok('Tag updated successfully');
        }

        return error('Tag not found', type: 'notfound');
    }

    public function delete($id)
    {
        $tag = Tag::find($id);

        if ($tag) {
            $tag->delete();
            return ok('Tag deleted successfully');
        }

        return error('Tag not found', type: 'notfound');
    }
}
