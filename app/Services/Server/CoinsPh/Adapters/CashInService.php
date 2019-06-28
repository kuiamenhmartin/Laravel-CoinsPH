<?php

namespace App\Services\Server\CoinsPh\Adapters;

use App\Exceptions\CustomException;

use App\Services\Server\CoinsPh\Interfaces\CashInInterface;

class CashInService implements CashInInterface
{
    public function __construct()
    {
        //get token
    }

    /**
     * Create a new buyorder
     * @method createNewBuyer
     * @param  array   $data data that holds api id
     * @return void
     */
    public function createNewBuyer(): void
    {

    }


    public function getExistingBuyer()
    {
        //
    }

    public function markBuyOrderAsPaid()
    {
        //
    }
}
