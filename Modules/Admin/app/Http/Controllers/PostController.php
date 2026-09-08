<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\Contracts\PostServiceInterface;

class PostController extends Controller
{
    public function __construct(
        protected PostServiceInterface $postService
    ) {}

    public function index()
    {
        return view('admin::posts.index');
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'post');

        return view('admin::posts.create', compact('type'));
    }

    public function edit(int $id)
    {
        $post = $this->postService->getPostById($id);
        if (! $post) {
            abort(404, 'Post or Page not found.');
        }

        return view('admin::posts.edit', compact('post'));
    }
}
