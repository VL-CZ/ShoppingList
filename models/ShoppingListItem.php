<?php


class ShoppingListItem
{
    public $id;
    public $name;
    public $amount;
    public $position;

    /**
     * ShoppingListItem constructor.
     * @param $name
     * @param $amount
     */
    public function __construct($id, $name, $amount, $position)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->position = $position;
    }

    /**
     * load the object from database row
     * @param $dbRow
     * @return ShoppingListItem
     */
    public static function loadFromDbRow($dbRow)
    {
        $id = $dbRow['id'];
        $name = $dbRow['name'];
        $amount = $dbRow['amount'];
        $position = $dbRow['position'];

        return new ShoppingListItem($id, $name, $amount, $position);
    }
}