<?php

namespace App\Interfaces;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function allPaginated(int $perPage = 10, ?string $search = null) : LengthAwarePaginator;
    public function find(int $id) : ?Post;
    public function create(array $data) : Post;
    public function update(int $id, array $data) : ?Post;
    public function delete(int $id) : bool;
    public function forUser(int $userId) : Collection;
}
