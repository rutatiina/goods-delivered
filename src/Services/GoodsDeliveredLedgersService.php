<?php

namespace Rutatiina\GoodsDelivered\Services;

use Rutatiina\GoodsDelivered\Models\GoodsDeliveredItem;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredItemTax;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredLedger;

class GoodsDeliveredLedgersService
{
    public static $errors = [];

    public function __construct()
    {
        //
    }

    public static function store($data)
    {
        foreach ($data['ledgers'] as &$ledger)
        {
            $ledger['goods_delivered_id'] = $data['id'];
            GoodsDeliveredLedger::create($ledger);
        }
        unset($ledger);

    }

}
