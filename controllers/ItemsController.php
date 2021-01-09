<?php
require_once __DIR__ . '/../models/ShoppingListItem.php';
require_once __DIR__ . '/../models/Storage.php';
require_once __DIR__ . '/../models/ResponseModel.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../models/Repository.php';

class ItemsController
{
    private $listItemsRepository;

    /**
     * ItemsController constructor
     */
    public function __construct()
    {
        $this->listItemsRepository = new ListItemsRepository();
    }


    public function getItems()
    {
        (new ListItemsRepository())->getAll();
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
        try
        {
            $this->listItemsRepository->deleteById($id);
            return new JsonOkResponseModel(null);
        }
        catch (Exception $e)
        {
            return new JsonErrorResponseModel("Error while deleting a user");
        }
    }

    public function postMoveItem($id, $direction)
    {
        Storage::move(intval($id), $direction);
        return new RedirectResponseModel(Router::$homePageAddress);
    }
}