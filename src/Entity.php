<?php

namespace App;

use PDO;
use PDOException;
use App\Exception\EntityException;

class Entity
{

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const ARCHIVED = 'archived';

    private $db;
    private $query;
    private $results;
    protected $instance;
    private $parameters = array();

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

    protected $validConditions = array(
        '=' => '= ?',
        '!=' => '!= ?',
        '<>' => '<> ?',
        '<' => '< ?',
        '<=' => '<= ?',
        '>' => '> ?',
        '>=' => '>= ?',
        'IN' => 'IN(?)',
        'NOT IN' => 'NOT IN(?)'
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
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
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
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
        }
    }

    public function select(array $columns)
    {
        try
        {
            $class = get_called_class();
            $instance = new $class();

            $selectedColumns = array();
            foreach($columns as $column)
                $selectedColumns[] = trim($column);

            $instance->query = 'SELECT ' . implode(', ', $selectedColumns) . PHP_EOL . 'FROM ' . $instance->table . PHP_EOL;

            return $instance;
        }
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
        }
    }

    public function where(string $column, string $condition, $value)
    {
        try
        {
            if(!isset($this->validConditions[$condition]))
                throw new EntityException('Invalid condition.');

            if(!str_contains($this->query, 'WHERE'))
                $this->query .= 'WHERE ' . $column . ' ' . $this->validConditions[$condition];
            elseif(substr($this->query, -1) === '(')
                $this->query .= PHP_EOL . $column . ' ' . $this->validConditions[$condition];
            else
                $this->query .= PHP_EOL . 'AND ' . $column . ' ' . $this->validConditions[$condition];

            if(in_array($condition, array('IN', 'NOT IN')) && is_array($value))
            {
                $placeholders = implode(',', array_fill(0, count($value), '?'));
                $this->parameters = array_merge($this->parameters, $value);
                $this->query = str_replace('?', $placeholders, $this->query);
            }
            else
                $this->parameters[] = $value;

            return $this;
        }
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
        }
    }

    public function orWhere(string $column, string $condition, $value)
    {
        try
        {
            $this->where($column, $condition, $value);

            if(!$lastPos = strrpos($this->query, 'AND'))
                throw new EntityException('Erreur dans la requête : "AND" introuvable dans la méthode orWhere().');

            $this->query = substr_replace($this->query, 'OR', $lastPos, strlen('AND'));

            return $this;
        }
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
        }
    }

    public function whereSub(string $operator, callable $callback)
    {
        $this->query .= PHP_EOL . $operator . ' (';

        $callback($this);

        $this->query .= PHP_EOL . ')';

        return $this;
    }

    public function run()
    {
        try
        {
            $this->query .= ';';
            $this->results = $this->db->prepare($this->query);

            $this->results->execute($this->parameters);

            if($_ENV['APP_ENV'] === 'dev')
                file_put_contents(__ROOT__ . 'sqldebug.sql', PHP_EOL . date('Y-m-d H:i:s') . ' | ' . $this->query . PHP_EOL, FILE_APPEND);

            return $this->results;
        }
        catch(PDOException $error)
        {
            throw new EntityException($error->getMessage(), $error->getCode(), $error);
        }
    }
}