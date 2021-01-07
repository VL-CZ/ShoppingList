<?php


class Storage
{
    private static $fileName = __DIR__ . '/data.json';

    private static function addId($item)
    {
        $items = self::loadData();
        $last = end($items);

        if (is_null($last))
            $item->id = 1;
        else
            $item->id = $last->id + 1;
    }

    private static function addOrder($item)
    {
        $items = self::getSortedData();
        $last = end($items);

        if (is_null($last))
            $item->order = 1;
        else
            $item->order = $last->order + 1;
    }

    private static function loadData()
    {
        $data = file_get_contents(self::$fileName);
        return json_decode($data);
    }

    private static function getSortedData()
    {
        $data = self::loadData();
        usort($data, function ($a, $b)
        {
            if ($a->order === $b->order)
            {
                return 0;
            }
            return ($a->order < $b->order) ? -1 : 1;
        });

        return $data;
    }

    private static function storeData($items)
    {
        $dataJson = json_encode($items);
        file_put_contents(self::$fileName, $dataJson);
    }

    public static function getAll()
    {
        return self::getSortedData();
    }

    public static function getById($id)
    {
        $items = self::loadData();
        foreach ($items as $item)
        {
            if ($item->id === $id)
                return $item;
        }

        return null;
    }

    public static function add($item)
    {
        $items = self::loadData();
        self::addId($item);
        self::addOrder($item);
        array_push($items, $item);
        self::storeData($items);
    }

    public static function deleteById($id)
    {
        $resultData = [];
        $items = self::loadData();
        foreach ($items as $item)
        {
            if ($item->id !== $id)
                $resultData[] = $item;
        }
        self::storeData($resultData);
    }

    public static function updateAmount($id, $newAmount)
    {
        $items = self::loadData();
        foreach ($items as $item)
        {
            if ($item->id === $id)
                $item->amount = $newAmount;
        }
        self::storeData($items);
    }


    public static function changeOrder($id, $direction)
    {

    }
}