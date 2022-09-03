<?php

namespace Rutatiina\GoodsDelivered\Services;

use Rutatiina\Item\Models\Item;
use Rutatiina\Inventory\Models\Inventory;
use Rutatiina\FinancialAccounting\Services\AccountBalanceUpdateService;
use Rutatiina\FinancialAccounting\Services\ContactBalanceUpdateService;

trait GoodsDeliveredInventoryService
{
    private static function record($transaction, $item)
    {
        $item['batch'] = (isset($item['batch'])) ? $item['batch'] : null;

        $inventory = Inventory::whereDate('date', '<=', $transaction['date'])
            ->where([
                'tenant_id' => $item['tenant_id'], 
                'project_id' => @$transaction['project_id'], 
                'item_id' => $item['item_id'],
                'batch' => $item['batch'],
            ])
            ->orderBy('date', 'desc')
            ->first();

        if ($inventory)
        {
            //create a new row with the last balances
            if ($transaction['date'] == $inventory->date)
            {
                //do nothing because the records for this dates balances already exists
            }
            else
            {
                $inventoryModel = new Inventory;
                $inventoryModel->tenant_id = $item['tenant_id'];
                $inventoryModel->date = $transaction['date'];
                $inventoryModel->item_id = $item['item_id'];
                $inventoryModel->batch = $item['batch'];
                $inventoryModel->units_received = $inventory->units_received;
                $inventoryModel->units_delivered = $inventory->units_delivered;
                $inventoryModel->units_issued = $inventory->units_issued;
                $inventoryModel->units_returned = $inventory->units_returned;
                $inventoryModel->units_available = $inventory->units_available;
                $inventoryModel->save();
            }

        }
        else
        {

            //create a new balance record
            $inventoryModel = new Inventory;
            $inventoryModel->tenant_id = $item['tenant_id'];
            $inventoryModel->date = $transaction['date'];
            $inventoryModel->item_id = $item['item_id'];
            $inventoryModel->batch = $item['batch'];
            $inventoryModel->save();

        }

        return Inventory::whereDate('date', '>=', $transaction['date'])
            ->where([
                'tenant_id' => $item['tenant_id'], 
                'project_id' => @$transaction['project_id'], 
                'item_id' => $item['item_id'],
                'batch' => $item['batch'],
            ]);
    }

    public static function update($data)
    {
        if ($data['status'] != 'approved') return false; //can only update balances if status is approved
        
        //Update the inventory summary
        foreach ($data['items'] as &$item)
        {
            if (!isset($item['inventory_tracking']))
            {
                if(is_numeric($item['item_id']))
                {
                    $item['inventory_tracking'] = optional(Item::find($item['item_id']))->inventory_tracking;
                }
                else
                {
                    continue;
                }
            }

            if ($item['inventory_tracking'] == 0) continue;

            $item['units'] = (is_numeric($item['units'])) ? $item['units'] : $item['quantity'];

            $inventory = self::record($data, $item);

            $inventory->increment('units_delivered', $item['units']);
            $inventory->decrement('units_available', $item['units']);

        }

        return true;
    }

    public static function reverse($data)
    {
        if ($data['status'] != 'approved') return false; //can only update balances if status is approved
        
        //Update the inventory summary
        foreach ($data['items'] as &$item)
        {
            if (!isset($item['inventory_tracking']))
            {
                if(is_numeric($item['item_id']))
                {
                    $item['inventory_tracking'] = optional(Item::find($item['item_id']))->inventory_tracking;
                }
                else
                {
                    continue;
                }
            }

            if ($item['inventory_tracking'] == 0) continue;

            $item['units'] = (is_numeric($item['units'])) ? $item['units'] : 0;
            
            $inventory = self::record($data, $item);

            $inventory->decrement('units_delivered', $item['units']);
            $inventory->increment('units_available', $item['units']);

        }

        return true;
    }

}
