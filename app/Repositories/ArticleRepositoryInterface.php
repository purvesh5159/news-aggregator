<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface {
    public function storeNormalized(array $data): Article;
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;
}
