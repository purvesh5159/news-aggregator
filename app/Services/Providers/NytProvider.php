<?php

namespace App\Services\Providers;

use App\Contracts\NewsProviderInterface;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NytProvider implements NewsProviderInterface {
    public function fetch(array $opts = []): array {
        $res = Http::get('https://api.nytimes.com/svc/topstories/v2/home.json', [
            'api-key' => config('services.nyt.key'),
        ]);

        if (!$res->ok()) return [];

        return collect($res->json('results'))->map(fn($a) => [
            'external_id'   => $a['uri'] ?? null,
            'source_code'   => 'nyt',
            'title'         => $a['title'] ?? '',
            'author'        => $a['byline'] ?? null,
            'url'           => $a['url'] ?? '',
            'summary'       => $a['abstract'] ?? null,
            'content'       => $a['abstract'] ?? null,
            'image'         => $a['multimedia'][0]['url'] ?? null,
            'category'      => $a['section'] ?? null,
            'published_at' => isset($a['published_date']) ? Carbon::parse($a['published_date']) : null,
            'raw'           => $a,
        ])->toArray();
    }
}
