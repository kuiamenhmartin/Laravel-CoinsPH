<?php

namespace App\Services\Server\CoinsPh\Interfaces;

interface CashInInterface
{
    public function createNewBuyer();

    public function getExistingBuyer();

    public function markBuyOrderAsPaid();
}
