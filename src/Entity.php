<?php

namespace App;

use PDO;
use App\Exception\EntityException;

class Entity
{

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const ARCHIVED = 'archived';

    private $db;
    private $query;
    private $results;

    protected $fieldsMapping = array(
        'text' => 'VARCHAR(255)',
        'password' => 'CHAR(60)',
        'datetime' => 'DATETIME'
    );

    protected $entityColumns = array(
        'state' => array('type' => 'text', 'label' => 'État logique de la donnée'),
        'created_at' => array('type' => 'datetime', 'label' => 'Date de création'),
        'creator' => array('type' => 'text', 'label' => 'Créateur'),
        'updated_at' => array('type' => 'datetime', 'label' => 'Date de modification'),
        'updator' => array('type' => 'text', 'label' => 'Modificateur')
    );

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        try
        {
            $this->db = new PDO('mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
        }
        catch(EntityException $error)
        {
            throw new EntityException($error->getMessage());
        }
    }

    public static function create()
    {
        try
        {
            $class = get_called_class();
            $instance = new $class();

            $instance->query = 'CREATE TABLE `' . $_ENV['DB_NAME'] . '`.`' . $instance->table . '` (
                `id` INT PRIMARY KEY AUTO_INCREMENT,
            ';

            $columns = array_merge($instance->columns, $instance->entityColumns);
            $lastKey = array_key_last($columns);
            foreach($columns as $key => $column)
            {
                $instance->query .= "`{$key}` {$instance->fieldsMapping[$column['type']]}";

                if($key !== $lastKey)
                    $instance->query .= ', ';
            }

            $instance->query .= PHP_EOL . ')';
            $instance->run();
        }
        catch(EntityException $error)
        {
            throw new EntityException($error->getMessage());
        }
    }

    public function select()
    {
        try
        {
            $class = get_called_class();
            $instance = new $class();

            $instance->query = 'SELECT
                    *
                FROM ' . $instance->table
            ;
            return $instance;
        }
        catch(EntityException $error)
        {
            throw new EntityException($error->getMessage());
        }
    }

    public function run()
    {
        try
        {
            $this->query .= ';';
            $this->results = $this->db->prepare($this->query);

            $this->results->execute();

            if($_ENV['APP_ENV'] === 'dev')
                file_put_contents(__ROOT__ . 'sqldebug.sql', PHP_EOL . date('Y-m-d H:i:s') . ' | ' . $this->query . PHP_EOL, FILE_APPEND);
        }
        catch(EntityException $error)
        {
            throw new EntityException($error->getMessage());
        }
    }
}