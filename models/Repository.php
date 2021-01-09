<?php

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

    public abstract function getAll();

    public abstract function getById($id);

    public abstract function add($item);

    public abstract function deleteById($id);
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
        $dataRows = $this->db->query('SELECT * from items INNER JOIN list ON list.item_id=items.id');
        foreach ($dataRows as $row)
        {
            $rows[] = $row;
        }
        $dbh = null;
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function add($item)
    {
        // TODO: Implement add() method.
    }

    public function deleteById($id)
    {
        $statement = $this->db->prepare('DELETE FROM list WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $result = $statement->execute();

        // TODO error check
        // if ($result === false)
    }

    public function updateAmount($id, $newAmount)
    {
        $statement = $this->db->prepare('UPDATE list SET list.amount=:newAmount WHERE list.id=:id');
        $statement->bindParam(':id', $id);
        $statement->bindParam(':newAmount', $newAmount);
        $result = $statement->execute();
    }
}