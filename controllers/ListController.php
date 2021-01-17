<?php
require_once __DIR__ . '/../models/ShoppingListItem.php';
require_once __DIR__ . '/../models/ResponseModel.php';
require_once __DIR__ . '/../router.php';
require_once __DIR__ . '/../models/Repository.php';
require_once __DIR__ . '/../models/DataValidator.php';

class ListController
{
    private $listRepository;
    private $itemsRepository;
    private $validator;

    /**
     * ItemsController constructor
     */
    public function __construct()
    {
        $this->listRepository = new ListItemsRepository();
        $this->itemsRepository = new ItemsRepository();
        $this->validator = new DataValidator();
    }

    /**
     * GET: List/Items
     * display all list items
     */
    public function getItems()
    {
        $listItems = $this->listRepository->getAll();
        $this->sortByPosition($listItems);

        $params = [
            'listItems' => $listItems,
            'allItems' => $this->itemsRepository->getAll()
        ];

        return new HtmlResponseModel('home', $params);
    }

    /**
     * POST: List/Item
     * add new item to the list
     * @param $name
     * @param $amount
     * @return RedirectResponseModel
     */
    public function postItem($name, $amount)
    {
        if (!$this->validator->isPositiveInteger($amount))
            throw new InvalidArgumentException();

        $listItem = new ShoppingListItem(0, $name, intval($amount), 0);

        $this->itemsRepository->tryToAddName($name);
        $this->listRepository->add($listItem);

        return new RedirectResponseModel(Router::$homePageAddress);
    }

    /**
     * POST: List/ItemUpdate
     * update amount of selected item
     * @param $id
     * @param $newAmount
     * @return RedirectResponseModel
     */
    public function postItemUpdate($id, $newAmount)
    {
        if (!$this->validator->isPositiveInteger($newAmount) || !$this->validator->isPositiveInteger($id))
            throw new InvalidArgumentException();

        $this->listRepository->updateAmount(intval($id), intval($newAmount));
        return new RedirectResponseModel(Router::$homePageAddress);
    }

    /**
     * DELETE: List/Item?id={id}
     * delete selected item by id
     * @param $id
     * @return JsonErrorResponseModel|JsonOkResponseModel
     */
    public function deleteItem($id)
    {
        try
        {
            if (!$this->validator->isPositiveInteger($id))
                throw new InvalidArgumentException();

            $this->listRepository->deleteById(intval($id));
            return new JsonOkResponseModel(null);
        }
        catch (Exception $e)
        {
            return new JsonErrorResponseModel("Error while deleting a user.");
        }
    }

    /**
     * POST: List/MoveItem
     * move item up/down in the list
     * @param $id
     * @param $direction 'up' OR 'down'
     * @return RedirectResponseModel
     */
    public function postMoveItem($id, $direction)
    {
        $allowedMoves = ['up', 'down'];
        if (!$this->validator->isPositiveInteger($id) || !in_array($direction, $allowedMoves))
            throw new InvalidArgumentException();

        $numericId = intval($id);
        if ($direction === $allowedMoves[0])
        {
            $this->listRepository->moveItemUp($numericId);
        }
        else if ($direction === $allowedMoves[1])
        {
            $this->listRepository->moveItemDown($numericId);
        }
        return new RedirectResponseModel(Router::$homePageAddress);
    }

    /**
     * sort items by position ascending
     * @param $data
     */
    private function sortByPosition(&$data)
    {
        usort($data, function ($a, $b)
        {
            if ($a->position === $b->position)
            {
                return 0;
            }
            return ($a->position < $b->position) ? -1 : 1;
        });
    }
}