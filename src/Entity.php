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
        'NOT IN' => 'NOT IN(?)',
        'BETWEEN' => 'BETWEEN ? AND ?'
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
        catch(PDOException|EntityException $error)
        {
            throw $error;
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
        catch(PDOException|EntityException $error)
        {
            throw $error;
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
        catch(PDOException|EntityException $error)
        {
            throw $error;
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
            elseif($condition === 'BETWEEN' && is_array($value))
                $this->parameters = array_merge($this->parameters, $value);
            else
                $this->parameters[] = $value;

            return $this;
        }
        catch(PDOException|EntityException $error)
        {
            throw $error;
        }
    }

    public function orWhere(string $column, string $condition, $value)
    {
        try
        {
            if(substr($this->query, -1) === '(')
                throw new PDOException('Impossible d\'appeler la méthode orWhere() au début d\'un whereSub()');

            $this->where($column, $condition, $value);

            if(!$lastPos = strrpos($this->query, 'AND'))
                throw new PDOException('Erreur dans la requête : "AND" introuvable dans la méthode orWhere().');

            if(strrpos($this->query, 'BETWEEN') < $lastPos)
                $lastPos = strrpos($this->query, 'AND', $lastPos - strlen($this->query) - 1);

            if(strrpos($this->query, '(') < $lastPos)
                $this->query = substr_replace($this->query, 'OR', $lastPos, strlen('AND'));

            return $this;
        }
        catch(PDOException|EntityException $error)
        {
            throw $error;
        }
    }

    public function whereSub(string $operator, callable $callback)
    {
        $this->query .= PHP_EOL . $operator . ' (';

        $callback($this);

        $this->query .= PHP_EOL . ')';

        return $this;
    }

    private function formatSqlQuery()
    {
        $formattedSql = '';
        $tabCount = 0;
        $lines = explode("\n", $this->query);

        foreach($lines as $line)
        {
            $line = trim($line);
            if(empty($line))
                continue;

            if(strpos($line, ')') !== false)
            {
                $tabCount--;

                if($tabCount < 0)
                    $tabCount = 0;
            }

            $formattedSql .= str_repeat("\t", $tabCount) . $line . "\n";

            if(strpos($line, '(') !== false)
                $tabCount++;
        }

        $this->query = $formattedSql;

        return $this;
    }

    public function run()
    {
        try
        {
            if($_ENV['APP_ENV'] === 'dev')
                $this->formatSqlQuery();

            $this->query .= ';';
            $this->results = $this->db->prepare($this->query);
            $this->results->execute($this->parameters);

            if($_ENV['APP_ENV'] === 'dev')
                file_put_contents(__ROOT__ . 'sqldebug.sql', PHP_EOL . date('Y-m-d H:i:s') . ' | ' . $this->query . PHP_EOL, FILE_APPEND);

            return $this->results;
        }
        catch(PDOException|EntityException $error)
        {
            throw $error;
        }
    }
}