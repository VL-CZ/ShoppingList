<?php


class Item
{
    public $id;
    public $name;

    /**
     * Item constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * load the object from database row
     * @param $dbRow
     * @return Item
     */
    public static function loadFromDbRow($dbRow)
    {
        $id = $dbRow['id'];
        $name = $dbRow['name'];

        return new Item($id, $name);
    }
}