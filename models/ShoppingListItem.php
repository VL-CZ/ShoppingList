<?php


class ShoppingListItem
{
    private static $nextId = 0;

    public $id;
    public $name;
    public $amount;
    public $order;

    /**
     * ShoppingListItem constructor.
     * @param $name
     * @param $amount
     */
    public function __construct($name, $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->order = 0;

        $this->id = self::$nextId;
        self::$nextId++;
    }
}