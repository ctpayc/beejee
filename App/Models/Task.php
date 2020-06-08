<?php

namespace App\Models;

use PDO;

/**
 * Task model class
 */
class Task extends \Core\Model
{
    const LIMIT = 3;

    /**
     * Get all the tasks as an associative array
     *
     * @return array
     */
    public static function getAll($params)
    {
        $db = static::getDB();
        $sql = self::generateQuery($params);
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($params)
    {
        $db = static::getDB();
        $sql = "INSERT INTO tasks (status, description, email, author_name)
                                       VALUES (:status, :description, :email, :author_name)";
        $stmt= $db->prepare($sql);

        return $stmt->execute($params);
    }

    public static function update($params)
    {
        $db = static::getDB();
        $sql = "UPDATE tasks
                SET status=:status, description=:description, edited=:edited
                WHERE id=:id";
        $stmt = $db->prepare($sql);

        return $stmt->execute($params);
    }

    public static function getById($id)
    {
        $db = static::getDB();
        $sql = "SELECT * FROM tasks WHERE id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    protected static function generateQuery($params = [])
    {
        $sql =  "SELECT * FROM tasks";

        if (isset($params['sort'])) {
            list($column, $method) = explode('|', $params['sort']);
            $sql .= " ORDER BY {$column} {$method}";
        }

        return $sql;
    }

}