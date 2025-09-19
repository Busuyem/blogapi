<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
    protected $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    //Post search & pagination
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 10);
            $search = $request->query('q', null);
            $paginated = $this->posts->allPaginated($perPage, $search);

            return response()->json($paginated);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to fetch posts', 'error'=>$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = $this->posts->find((int)$id);
            if (!$post) {
                return response()->json(['message'=>'Post not found'], 404);
            }
            return response()->json($post);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to fetch post', 'error'=>$e->getMessage()], 500);
        }
    }

    public function store(StorePostRequest $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $data = $request->only(['title','body']);
            $data['author_id'] = $user->id;

            $post = $this->posts->create($data);

            return response()->json($post, 201);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to create post', 'error'=>$e->getMessage()], 500);
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $post = $this->posts->find((int)$id);
            if (!$post) {
                return response()->json(['message'=>'Post not found'], 404);
            }
            if ($post->author_id !== $user->id) {
                return response()->json(['message'=>'Forbidden'], 403);
            }
            $updated = $this->posts->update((int)$id, $request->only(['title','body']));
            return response()->json($updated);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to update post', 'error'=>$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $post = $this->posts->find((int)$id);
            if (!$post) {
                return response()->json(['message'=>'Post not found'], 404);
            }
            if ($post->author_id !== $user->id) {
                return response()->json(['message'=>'Forbidden'], 403);
            }
            $this->posts->delete((int)$id);
            return response()->json(['message'=>'Deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to delete post', 'error'=>$e->getMessage()], 500);
        }
    }

    // List authenticated user's posts
    public function myPosts()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $posts = $this->posts->forUser($user->id);
            return response()->json($posts);
        } catch (\Throwable $e) {
            return response()->json(['message'=>'Unable to fetch your posts', 'error'=>$e->getMessage()], 500);
        }
    }
}
