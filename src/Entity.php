<?php

namespace App;

use PDO;
use PDOException;
use App\Exception\EntityException;

/**
 * @property array $columns
 * @property string $table
 */
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

    public $id, $state, $created_at, $creator, $updated_at, $updator;

    protected $fieldsMapping = array(
        'text' => 'VARCHAR(255)',
        'password' => 'CHAR(60)',
        'datetime' => 'DATETIME'
    );

    protected $entityColumns = array(
        'id' => array('type' => 'key', 'label' => 'Identifiant de la donnée'),
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
        catch(PDOException|EntityException $error)
        {
            throw $error;
        }
    }

    public static function tableName()
    {
        $class = get_called_class();
        $class = new $class()
        ;
        return $class->table;
    }

    public static function createTable()
    {
        try
        {
            $class = get_called_class();
            $instance = new $class();

            $instance->query = "CREATE TABLE `{$_ENV['DB_NAME']}`.`{$instance->table}` (
                `id` INT PRIMARY KEY AUTO_INCREMENT,
            ";

            $columns = $instance->getColumns();
            $lastKey = array_key_last($columns);
            foreach($columns as $key => $value)
            {
                if($key === 'id')
                    continue;

                $instance->query .= "`{$key}` {$instance->fieldsMapping[$value['type']]}";

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

    public function getColumns()
    {
        $class = get_called_class();
        $instance = new $class();

        $columns = array_merge($instance->columns, $instance->entityColumns);
        return $columns;
    }

    public function run()
    {
        try
        {
            foreach ($this->parameters as &$param)
                $param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');

            if($_ENV['APP_ENV'] === 'dev')
                $this->formatSqlQuery();

            $this->query .= ';';
            $this->results = $this->db->prepare($this->query);
            $this->results->execute($this->parameters);
            $this->results->setFetchMode(PDO::FETCH_ASSOC);

            if($_ENV['APP_ENV'] === 'dev')
                file_put_contents(__ROOT__ . 'sqldebug.sql', PHP_EOL . date('Y-m-d H:i:s') . ' | ' . $this->query . PHP_EOL, FILE_APPEND);

            return $this->results;
        }
        catch(PDOException|EntityException $error)
        {
            throw $error;
        }
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

            if(strpos($line, ')') !== false)
            {
                $tabCount--;

                if($tabCount < 0)
                    $tabCount = 0;
            }
        }

        $this->query = $formattedSql;

        return $this;
    }

    protected function save()
    {
        try
        {
            if(!empty($this->id) && $this->id > 0)
            {
                $this->update();
            }
            else
            {
                $this->create();
            }
        }
        catch (PDOException $error)
        {
            throw new EntityException("Erreur lors de l'enregistrement : " . $error->getMessage());
        }
    }

    protected function create()
    {
        try
        {
            $columns = $this->getColumns();

            $this->query = 'INSERT INTO ' . $this::tableName() . ' (';

            $lastKey = array_key_last($columns);
            foreach(array_keys($columns) as $key)
            {
                if($key !== $lastKey)
                    $this->query .= $key . ', ';
                else
                    $this->query .= $key;
            }

            $this->query .= ') VALUES (';

            foreach(array_keys($columns) as $key)
            {
                $this->parameters[] = htmlspecialchars($this->$key, ENT_QUOTES, 'UTF-8');

                if($key !== $lastKey)
                    $this->query .= '?, ';
                else
                    $this->query .= '?';
            }

            $this->query .= ')';
            $this->run();
        }
        catch (PDOException $error)
        {
            throw new EntityException("Erreur lors de la création : " . $error->getMessage());
        }
    }

    protected function update()
    {
        try
        {
            $columns = $this->getColumns();
            $this->query = "UPDATE {$this::tableName()} SET ";

            foreach(array_keys($columns) as $key)
            {
                if($key === 'id')
                    continue;

                $this->parameters[] = htmlspecialchars($this->$key, ENT_QUOTES, 'UTF-8');

                if($key !== array_key_last($columns))
                    $this->query .= "{$key} = ?, ";
                else
                    $this->query .= "{$key} = ?";
            }

            $this->query .= " WHERE id = {$this->id}";
            $this->run();
        }
        catch (PDOException $error)
        {
            throw new EntityException("Erreur lors de la mise à jour : " . $error->getMessage());
        }
    }

    // public function select(array $columns)
    // {
    //     try
    //     {
    //         $class = get_called_class();
    //         $instance = new $class();

    //         $selectedColumns = array();
    //         foreach($columns as $column)
    //             $selectedColumns[] = trim($column);

    //         $instance->query = 'SELECT ' . implode(', ', $selectedColumns) . PHP_EOL . 'FROM ' . $instance->table . PHP_EOL;

    //         return $instance;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // public function where(string $column, string $condition, $value)
    // {
    //     $validConditions = array(
    //         '=' => '= ?',
    //         '!=' => '!= ?',
    //         '<>' => '<> ?',
    //         '<' => '< ?',
    //         '<=' => '<= ?',
    //         '>' => '> ?',
    //         '>=' => '>= ?',
    //         'IN' => 'IN(?)',
    //         'NOT IN' => 'NOT IN(?)',
    //         'BETWEEN' => 'BETWEEN ? AND ?'
    //     );

    //     try
    //     {
    //         if(!isset($validConditions[$condition]))
    //             throw new EntityException('Invalid condition.');

    //         if(!str_contains($this->query, 'WHERE'))
    //             $this->query .= 'WHERE ' . $column . ' ' . $validConditions[$condition];
    //         elseif(substr($this->query, -1) === '(')
    //             $this->query .= PHP_EOL . $column . ' ' . $validConditions[$condition];
    //         else
    //             $this->query .= PHP_EOL . 'AND ' . $column . ' ' . $validConditions[$condition];

    //         if(in_array($condition, array('IN', 'NOT IN')) && is_array($value))
    //         {
    //             $placeholders = implode(',', array_fill(0, count($value), '?'));
    //             $this->parameters = array_merge($this->parameters, $value);
    //             $this->query = str_replace('?', $placeholders, $this->query);
    //         }
    //         elseif($condition === 'BETWEEN' && is_array($value))
    //             $this->parameters = array_merge($this->parameters, $value);
    //         else
    //             $this->parameters[] = $value;

    //         return $this;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // public function orWhere(string $column, string $condition, $value)
    // {
    //     try
    //     {
    //         if(substr($this->query, -1) === '(')
    //             throw new EntityException('Impossible d\'appeler la méthode orWhere() au début d\'un whereSub()');

    //         $this->where($column, $condition, $value);

    //         if(!$lastPos = strrpos($this->query, 'AND'))
    //             throw new EntityException('Erreur dans la requête : "AND" introuvable dans la méthode orWhere().');

    //         if(strrpos($this->query, 'BETWEEN') < $lastPos)
    //             $lastPos = strrpos($this->query, 'AND', $lastPos - strlen($this->query) - 1);

    //         if(strrpos($this->query, '(') < $lastPos)
    //             $this->query = substr_replace($this->query, 'OR', $lastPos, strlen('AND'));

    //         return $this;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // public function whereSub(string $operator, callable $callback)
    // {
    //     $this->query .= PHP_EOL . $operator . ' (';

    //     $callback($this);

    //     $this->query .= PHP_EOL . ')';

    //     return $this;
    // }

    // public function groupBy(array $columns)
    // {
    //     try
    //     {
    //         if(empty($columns))
    //             throw new EntityException('Impossible d\'utiliser une clause GROUP BY sans indiquer de colonne.');

    //         $this->query .= PHP_EOL . 'GROUP BY ' . implode(', ', $columns);

    //         return $this;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // public function orderBy(array $columnsToOrder)
    // {
    //     try
    //     {
    //         $this->query .= PHP_EOL . 'ORDER BY ';

    //         $concatenate = array();
    //         foreach($columnsToOrder as $column => $order)
    //         {
    //             $order = strtoupper($order);
    //             if(empty($order))
    //                 $concatenate[] = $column . ' ASC';
    //             elseif($order != 'DESC' && $order != 'ASC')
    //                 throw new EntityException('Seuls les valeurs ASC et DESC sont acceptés dans la clause ORDER BY.');
    //             else
    //                 $concatenate[] = $column . ' ' . $order;
    //         }

    //         $this->query .= implode(', ', $concatenate);

    //         return $this;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // public function limit(int $limit)
    // {
    //     try
    //     {
    //         if(empty($limit))
    //             throw new EntityException('La limite ne peut être nulle.');

    //         $this->query .= PHP_EOL . 'LIMIT ' . $limit;

    //         return $this;
    //     }
    //     catch(PDOException|EntityException $error)
    //     {
    //         throw $error;
    //     }
    // }

    // // public function getColumns()
    // // {
    // //     if (method_exists($this, 'getColumnsFromChild'))
    // //         return $this->getColumnsFromChild();
    // //     else
    // //         return null;
    // // }

    // public function tableName()
    // {
    //     return $this->table;
    // }

    // protected function create()
    // {
    //     $columns = array();
    //     $class = get_called_class();

    //     if(property_exists($this, 'columns'))
    //         $columns = array_merge($this->columns, $this->entityColumns);
    //     else
    //         throw new EntityException('$this->columns n\'est pas définie dans ' . $class);

    //     $query = 'INSERT INTO ' . $class::tableName() . ' (';

    //     $lastKey = array_key_last($columns);
    //     foreach($columns as $key => $value)
    //     {
    //         if($key !== $lastKey)
    //             $query .= $key . ', ';
    //         else
    //             $query .= $key;
    //     }

    //     $query .= ') VALUES (';

    //     dd($this);
    // }

    // public function save()
    // {
    //     if(!empty($this->id) && $this->id > 0)
    //     {
    //         // update
    //     }
    //     else
    //     {
    //         $this->create();
    //     }
    // }
}