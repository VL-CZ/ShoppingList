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

    public function updateItem($id, $newAmount)
    {
        $item = Storage::getById($id);
        return ["ok" => "true"];
    }

    public function deleteItem($id)
    {
        $item = Storage::getById($id);
        return ["ok" => "true"];
    }
}