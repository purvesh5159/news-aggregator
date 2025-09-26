<?php

namespace App\Services\Providers;

use App\Contracts\NewsProviderInterface;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NewsApiProvider implements NewsProviderInterface
{
    protected $key;
    protected $sourceCode = 'newsapi';

    public function __construct()
    {
        $this->key = config('services.newsapi.key') ?? env('NEWSAPI_KEY');
    }

    public function fetch(array $opts = []): array
    {
        // simple example: fetch top-headlines or everything with q
        $params = [
            'apiKey' => $this->key,
            'language' => $opts['language'] ?? 'en',
            'pageSize' => $opts['pageSize'] ?? 50,
        ];
        if (!empty($opts['q'])) $params['q'] = $opts['q'];
        if (!empty($opts['sources'])) $params['sources'] = $opts['sources'];

        $res = Http::get('https://newsapi.org/v2/top-headlines', $params);
        if (!$res->ok()) return [];

        $payload = $res->json();
        $articles = $payload['articles'] ?? [];

        $out = [];
        foreach ($articles as $a) {
            $out[] = [
                'external_id' => $a['source']['id'] ?? null,
                'source_code' => $this->sourceCode,
                'title' => $a['title'] ?? 'No title',
                'author' => $a['author'] ?? null,
                'url' => $a['url'] ?? null,
                'summary' => $a['description'] ?? null,
                'content' => $a['content'] ?? null,
                'image' => $a['urlToImage'] ?? null,
                'category' => $opts['category'] ?? null,
                'published_at' => isset($a['publishedAt']) ? Carbon::parse($a['publishedAt']) : null,
                'raw' => $a,
            ];
        }
        return $out;
    }
}
