<?php

namespace App\Services;

use App\Contracts\NewsProviderInterface;
use App\Repositories\ArticleRepositoryInterface;

class ArticleAggregator
{
    protected $providers;
    protected $repo;

    public function __construct(iterable $providers, ArticleRepositoryInterface $repo)
    {
        $this->providers = $providers;
        $this->repo = $repo;
    }

    public function fetchAndStore(array $opts = [])
    {
        foreach ($this->providers as $provider) {
            $articles = $provider->fetch($opts);
            foreach ($articles as $a) {
                if (empty($a['url']) || empty($a['title'])) continue;
                $this->repo->storeNormalized($a);
            }
        }
    }
}
