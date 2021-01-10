<?php
require_once __DIR__ . '/ShoppingListItem.php';
require_once __DIR__ . '/Item.php';

abstract class Repository
{
    /**
     * @var PDO active DB connection
     */
    protected $db;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        require __DIR__ . '/../db_config.php'; // get the db_config

        $server = $db_config['server']; // extract the data from db_config
        $login = $db_config['login'];
        $password = $db_config['password'];
        $database = $db_config['database'];

        try
        {
            $this->db = new PDO("mysql:host=$server;dbname=$database", $login, $password);
        }
        catch (PDOException $e)
        {
            throw new InvalidArgumentException("Invalid DB arguments");
        }
    }

    /**
     * get all objects
     * @return mixed
     */
    public abstract function getAll();

    /**
     * add object to repository
     * @param $object
     * @return mixed
     */
    public abstract function add($object);
}

class ListItemsRepository extends Repository
{
    /**
     * ListItemsRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $listItems = [];
        $dataRows = $this->db->query('SELECT list.id,list.amount,list.position,items.name FROM items INNER JOIN list ON list.item_id=items.id');

        foreach ($dataRows as $row)
        {
            $listItems[] = ShoppingListItem::loadFromDbRow($row);
        }

        return $listItems;
    }

    public function add($object)
    {
        $position = $this->getMaxPosition() + 1;
        $amount = $object->amount;
        $item_id = $this->getItemIdByName($object->name);

        $statement = $this->db->prepare('INSERT INTO list (position,amount,item_id) VALUES (:position,:amount,:item_id)');
        $statement->bindParam(':position', $position);
        $statement->bindParam(':amount', $amount);
        $statement->bindParam(':item_id', $item_id);
        $result = $statement->execute();
    }

    /**
     * delete object by its id
     * @param $id
     */
    public function deleteById($id)
    {
        $position = $this->getPositionById($id);

        $statement = $this->db->prepare('DELETE FROM list WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $result = $statement->execute();

        // TODO error check
        // if ($result === false)

        // decrement following positions
        $this->decrementPositionsAfter($position);
    }

    /**
     * update amount of the list item
     * @param $id
     * @param $newAmount
     */
    public function updateAmount($id, $newAmount)
    {
        $statement = $this->db->prepare('UPDATE list SET list.amount=:newAmount WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $statement->bindParam(':newAmount', $newAmount);
        $result = $statement->execute();
    }

    public function moveItemUp($id)
    {
        $this->moveItem($id, true);
    }

    public function moveItemDown($id)
    {
        $this->moveItem($id, false);
    }

    /**
     * get maximal value of position column of the list items
     * @return int
     */
    private function getMaxPosition()
    {
        $result = $this->db->query('SELECT MAX(list.position) FROM list');
        $maxPosition = $result->fetchColumn();
        return intval($maxPosition);
    }

    /**
     * move item position up/down
     * @param $id
     * @param $isMoveUp bool true = up, false = down
     */
    private function moveItem($id, $isMoveUp)
    {
        $position = $this->getPositionById($id);
        $shift = $isMoveUp ? -1 : 1;
        $otherItemPosition = $position + $shift;

        $otherItemId = $this->getIdByPosition($otherItemPosition);

        if (!is_null($otherItemId))
        {
            // swap positions with the following item
            $this->updatePosition($id, $otherItemPosition);
            $this->updatePosition($otherItemId, $position);
        }
    }

    /**
     * get item ID by its name
     */
    private function getItemIdByName($name)
    {
        $statement = $this->db->prepare('SELECT items.id FROM items WHERE items.name=:name');
        $statement->bindParam(':name', $name);
        $statement->execute();
        $result = $statement->fetchAll();

        $record = $result[0];

        return intval($record['id']);
    }

    /**
     * get position of the item with selected id
     * @param $id
     * @return int
     */
    private function getPositionById($id)
    {

        $statement = $this->db->prepare('SELECT list.position FROM list WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $statement->execute();

        $position = $statement->fetchColumn();
        return intval($position);
    }

    /**
     * decrement by one positions after selected position
     * @param $position
     */
    private function decrementPositionsAfter($position)
    {
        $statement = $this->db->prepare('UPDATE list SET list.position=list.position-1 WHERE list.position > :position');
        $statement->bindParam(':position', $position);
        $statement->execute();
    }

    /**
     * get list item Id by its position
     * @return int or NULL if not found
     */
    private function getIdByPosition($position)
    {
        $statement = $this->db->prepare('SELECT list.id FROM list WHERE list.position=:position');
        $statement->bindParam(':position', $position);
        $statement->execute();

        if ($statement->rowCount() > 0)
        {
            $id = $statement->fetchColumn();
            return intval($id);
        }
        else
        {
            return null;
        }

    }

    /**
     * update position of the item with selected ID
     * @param $id
     * @param $newPosition
     */
    private function updatePosition($id, $newPosition)
    {
        $statement = $this->db->prepare('UPDATE list SET list.position=:newPosition WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $statement->bindParam(':newPosition', $newPosition);
        $result = $statement->execute();
    }
}

class ItemsRepository extends Repository
{
    /**
     * ItemsRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $items = [];
        $dataRows = $this->db->query('SELECT items.id,items.name FROM items');

        foreach ($dataRows as $row)
        {
            $items[] = Item::loadFromDbRow($row);
        }

        return $items;
    }

    public function add($object)
    {
        $statement = $this->db->prepare('INSERT INTO items (name) VALUES (:name)');
        $statement->bindParam(':name', $object->name);
        $result = $statement->execute();
    }

    /**
     * add the name to DB if not present
     * otherwise do nothing
     * @param $name
     */
    public function tryToAddName($name)
    {
        // check if the name is contained in the DB
        $statement = $this->db->prepare('SELECT COUNT(*) FROM items WHERE items.name=:name');
        $statement->bindParam(':name', $name);
        $statement->execute();
        $occurences = $statement->fetchColumn();

        // if not present, add it into DB
        if ($occurences == 0)
            $this->add(new Item(0, $name));
    }
}