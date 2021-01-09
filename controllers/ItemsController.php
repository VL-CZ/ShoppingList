<?php
require_once __DIR__ . '/../models/ShoppingListItem.php';
require_once __DIR__ . '/../models/Storage.php';
require_once __DIR__ . '/../models/ResponseModel.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../models/Repository.php';

class ItemsController
{
    private $listRepository;
    private $itemsRepository;

    /**
     * ItemsController constructor
     */
    public function __construct()
    {
        $this->listRepository = new ListItemsRepository();
        $this->itemsRepository = new ItemsRepository();
    }


    public function getItems()
    {
        $items = $this->listRepository->getAll();
        require __DIR__ . '/../templates/home.php';
        die();
    }

    public function postItem($name, $amount)
    {
        $listItem = new ShoppingListItem(0, $name, intval($amount), 0);

        $this->itemsRepository->tryToAddName($name);
        $this->listRepository->add($listItem);

        return new RedirectResponseModel(Router::$homePageAddress);
    }

    public function postItemUpdate($id, $newAmount)
    {
        $this->listRepository->updateAmount(intval($id), intval($newAmount));
        return new RedirectResponseModel(Router::$homePageAddress);
    }

    public function deleteItem($id)
    {
        try
        {
            $this->listRepository->deleteById(intval($id));
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