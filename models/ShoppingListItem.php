<?php


class ShoppingListItem
{
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
    }
}