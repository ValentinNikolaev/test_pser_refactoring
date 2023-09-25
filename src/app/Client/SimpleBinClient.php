<?php

declare(strict_types=1);

namespace App\Client;

interface SimpleBinClient
{
    public function getById(int $id): object;
}