<?php
require_once __DIR__ . '/../models/ShoppingListItem.php';
require_once __DIR__ . '/../models/Storage.php';

class ItemsController
{
    public function getItems()
    {
        $items = Storage::getAll();
        require __DIR__ . '/../templates/home.php';
        die();
    }

    public function postItem($name, $amount)
    {
        $item = new ShoppingListItem($name, $amount);
        Storage::add($item);
        return ["ok" => "true"];
    }

    public function postItemUpdate($id, $newAmount)
    {
        Storage::updateAmount(intval($id), intval($newAmount));
        return ["ok" => "true"];
    }

    public function deleteItem($id)
    {
        $numericId = intval($id);
        Storage::deleteById($numericId);
        return ["ok" => "true"];
    }
}