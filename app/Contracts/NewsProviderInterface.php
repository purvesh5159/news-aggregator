<?php

namespace App\Contracts;

interface NewsProviderInterface {
    public function fetch(array $opts = []): array;
}
