<?php

namespace App\Services;

use App\User;

interface ActionInterface
{
    public function execute(array $data);
}
