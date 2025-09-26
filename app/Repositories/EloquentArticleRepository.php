<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentArticleRepository implements ArticleRepositoryInterface
{
    public function storeNormalized(array $data): Article
    {
        // dedupe by url
        $article = Article::firstOrNew(['url' => $data['url']]);
        $article->fill($data);
        $article->save();
        return $article;
    }

    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Article::query();

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function($q2) use ($q) {
                $q2->where('title','like',"%{$q}%")
                   ->orWhere('summary','like',"%{$q}%")
                   ->orWhere('content','like',"%{$q}%");
            });
        }

        if (!empty($filters['source'])) $query->whereIn('source_code', (array)$filters['source']);
        if (!empty($filters['category'])) $query->whereIn('category', (array)$filters['category']);

        if (!empty($filters['date_from'])) $query->where('published_at', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->where('published_at', '<=', $filters['date_to']);

        if (!empty($filters['sort']) && $filters['sort'] === 'oldest') {
            $query->orderBy('published_at','asc');
        } else {
            $query->orderBy('published_at','desc');
        }

        return $query->paginate($perPage);
    }
}
