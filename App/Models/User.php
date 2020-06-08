<?php

namespace App\Models;

use PDO;

/**
 * User model class
 */
class User extends \Core\Model
{

    /**
     * check user creds
     *
     * @return bool
     */
    public static function auth($creds)
    {
        $db = static::getDB();
        $sql = "SELECT id FROM users WHERE email=:email and password=:password";

        $stmt= $db->prepare($sql);
        $stmt->execute($creds);
        return $stmt->fetch();
    }

}
