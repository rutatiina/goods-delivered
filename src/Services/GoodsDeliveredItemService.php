<?php

namespace Rutatiina\GoodsDelivered\Services;

use Rutatiina\GoodsDelivered\Models\GoodsDeliveredItem;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredItemTax;

class GoodsDeliveredItemService
{
    public static $errors = [];

    public function __construct()
    {
        //
    }

    public static function store($data)
    {
        //print_r($data['items']); exit;

        //Save the items >> $data['items']
        foreach ($data['items'] as &$item)
        {
            $item['goods_delivered_id'] = $data['id'];

            $itemTaxes = (is_array($item['taxes'])) ? $item['taxes'] : [] ;
            unset($item['taxes']);

            $itemModel = GoodsDeliveredItem::create($item);

        }
        unset($item);

    }

}
