<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleAggregator;

class FetchNews extends Command
{
    protected $signature = 'news:fetch {--q=} {--pageSize=50}';
    protected $description = 'Fetch latest news from providers';

    public function handle(ArticleAggregator $aggregator)
    {
        $opts = [
            'q' => $this->option('q'),
            'pageSize' => $this->option('pageSize'),
        ];

        $aggregator->fetchAndStore($opts);
        $this->info('Fetch complete.');
    }
}
