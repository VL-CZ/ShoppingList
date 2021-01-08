<?php
require_once __DIR__ . '/../models/ShoppingListItem.php';
require_once __DIR__ . '/../models/Storage.php';
require_once __DIR__ . '/../models/ResponseModel.php';
require_once __DIR__ . '/../router.php';

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
        return new RedirectResponseModel(Router::$homePageAddress);
    }

    public function postItemUpdate($id, $newAmount)
    {
        Storage::updateAmount(intval($id), intval($newAmount));
        return new RedirectResponseModel(Router::$homePageAddress);
    }

    public function deleteItem($id)
    {
        $numericId = intval($id);
        Storage::deleteById($numericId);
        return new JsonOkResponseModel(["ok" => "true"]);
    }

    public function postMoveItem($id, $direction)
    {
        Storage::move(intval($id), $direction);
        return new RedirectResponseModel(Router::$homePageAddress);
    }
}