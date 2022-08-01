<?php

namespace Rutatiina\GoodsDelivered\Services;

use Rutatiina\Inventory\Models\Inventory;
use Rutatiina\FinancialAccounting\Services\AccountBalanceUpdateService;
use Rutatiina\FinancialAccounting\Services\ContactBalanceUpdateService;

trait GoodsDeliveredInventoryService
{
    public static function update($data)
    {
        if ($data['status'] != 'approved')
        {
            //can only update balances if status is approved
            return false;
        }
        
        //Update the inventory summary
        foreach ($data['items'] as &$item)
        {
            $inventory = Inventory::firstOrCreate([
                'tenant_id' => $item['tenant_id'], 
                'project_id' => @$data['project_id'], 
                'date' => $data['date'],
                'item_id' => $item['item_id'],
                'batch' => $item['batch'],
            ]);

            $inventory->increment('units_delivered', $item['units']);
            $inventory->decrement('units_available', $item['units']);

        }

        return true;
    }

    public static function reverse($data)
    {
        //approve means the inventories for this transaction were updated
        //this reversing is allowed ONLY if status approved
        if ($data['status'] != 'approved')
        {
            return false;
        }
        
        //Update the inventory summary
        foreach ($data['items'] as &$item)
        {
            $inventory = Inventory::firstOrCreate([
                'tenant_id' => $item['tenant_id'], 
                'project_id' => @$data['project_id'], 
                'date' => $data['date'],
                'item_id' => $item['item_id'],
                'batch' => $item['batch'],
            ]);

            $inventory->decrement('units_delivered', $item['units']);
            $inventory->increment('units_available', $item['units']);

        }

        return true;
    }

}
