<?php

namespace App\Services\Providers;

use App\Contracts\NewsProviderInterface;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GuardianProvider implements NewsProviderInterface {
    public function fetch(array $opts = []): array {
        $res = Http::get('https://content.guardianapis.com/search', [
            'api-key'     => config('services.guardian.key'),
            'show-fields' => 'headline,byline,thumbnail,body',
            'page-size'   => 20,
        ]);

        if (!$res->ok()) return [];

        return collect($res->json('response.results'))->map(fn($a) => [
            'external_id'   => $a['id'] ?? null,
            'source_code'   => 'guardian',
            'title'         => $a['webTitle'] ?? '',
            'author'        => $a['fields']['byline'] ?? null,
            'url'           => $a['webUrl'] ?? '',
            'summary'       => $a['fields']['headline'] ?? null,
            'content'       => $a['fields']['body'] ?? null,
            'image'         => $a['fields']['thumbnail'] ?? null,
            'category'      => $a['sectionName'] ?? null,
            'published_at' => isset($a['webPublicationDate']) ? Carbon::parse($a['webPublicationDate']) : null,
            'raw'           => $a,
        ])->toArray();
    }
}
