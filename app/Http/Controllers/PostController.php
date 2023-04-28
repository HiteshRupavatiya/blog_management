<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\ListingApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    use ListingApiTrait;

    public function list(Request $request)
    {
        $this->ListingValidation();
        $query = Post::query();

        $query->where('user_id', Auth::user()->id);

        $searchableFields = ['title', 'description'];

        $data = $this->filterSearchPagination($query, $searchableFields);

        return ok('Posts fetched successfully', [
            'posts' => $data['query']->get(),
            'count' => $data['count']
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|min:5|max:100|unique:posts,title',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'post_tags'   => 'exists:tags,id'
        ]);

        $post = Post::create($request->only(
            [
                'title',
                'description',
                'category_id'
            ]
        ) + [
            'user_id' => Auth::user()->id
        ]);

        if ($request->filled('post_tags')) {
            $post->postTags()->attach($request->post_tags);
        }

        return ok('Post created successfully', $post);
    }

    public function get($id)
    {
        $post = Post::where('user_id', Auth::user()->id)->find($id);

        if ($post) {
            return ok('Post fetched successfully', $post);
        }

        return error('Post not found', type: 'notfound');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'string|min:5|max:100|unique:posts,title',
            'description' => 'string',
            'category_id' => 'exists:categories,id',
            'post_tags'   => 'exists:tags,id'
        ]);

        $post = Post::where('user_id', Auth::user()->id)->find($id);

        $title = $request->title ?? $post->title;
        $description = $request->description ?? $post->description;
        $category_id = $request->category_id ?? $post->category_id;

        if ($post) {
            $post->update([
                'title'       => $title,
                'description' => $description,
                'category_id' => $category_id
            ]);

            if ($request->filled('post_tags')) {
                $post->postTags()->sync($request->post_tags);
            }

            return ok('Post updated successfully');
        }
        return error('Post not found', type: 'notfound');
    }

    public function delete($id)
    {
        $post = Post::where('user_id', Auth::user()->id)->find($id);
        if ($post) {
            $post->delete();
            return ok('Post deleted successfully');
        }
        return error('Post not found', type: 'notfound');
    }
}
