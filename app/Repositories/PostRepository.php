<?php
namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function allPaginated(int $perPage = 10, ?string $search = null) : LengthAwarePaginator
    {
        try {
            $query = $this->model->with('author')->orderBy('created_at', 'desc');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('body', 'like', '%' . $search . '%');
                });
            }
            return $query->paginate($perPage);
        } catch (\Throwable $e) {
            Log::error('PostRepository allPaginated error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find(int $id) : ?Post
    {
        try {
            return $this->model->with('author')->find($id);
        } catch (\Throwable $e) {
            Log::error('PostRepository find error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data) : Post
    {
        try {
            return $this->model->create($data);
        } catch (\Throwable $e) {
            Log::error('PostRepository create error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data) : ?Post
    {
        try {
            $post = $this->model->findOrFail($id);
            $post->update($data);
            return $post;
        } catch (\Throwable $e) {
            Log::error('PostRepository update error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id) : bool
    {
        try {
            $post = $this->model->findOrFail($id);
            return $post->delete();
        } catch (\Throwable $e) {
            Log::error('PostRepository delete error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function forUser(int $userId) : Collection
    {
        try {
            return $this->model->where('author_id', $userId)->get();
        } catch (\Throwable $e) {
            Log::error('PostRepository forUser error: ' . $e->getMessage());
            throw $e;
        }
    }
}
