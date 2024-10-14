<?php

namespace App\Services\Contracts;

interface HttpClientInterface
{


    public function get(string $url, array $options = []);

    public function jsonToArray(): array;
}
