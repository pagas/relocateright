<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 05/11/2017
 * Time: 10:09
 */
class Db {
    private static $connection;

    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    public static function connect($host, $user, $password, $database)
    {
        if (!isset(self::$connection))
        {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$settings
            );
            if (!self::$connection) {
                die('Connection failed' . mysqli_connect_error());
            }
        }
    }

    public static function queryOne($table, $params = array())
    {
        $whereStr = DB::generateWhereString($params);
        $query = "SELECT * FROM $table WHERE $whereStr";

        $result = self::$connection->prepare($query);
        $result->execute($params);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public static function queryAll($query, $params = array())
    {
        $result = self::$connection->prepare($query);
        $result->execute($params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Executes a query and returns the number of affected rows
    public static function query($query, $params = array())
    {
        $result = self::$connection->prepare($query);
        $result->execute($params);
        return $result->rowCount();
    }

    public static function insert($table, $params = array())
    {
        $paramNames = join(',', array_keys($params));
        $paramPlaceholders = join(',', array_map(function($key) {
            return ':' . $key;
        }, array_keys($params)));

        $query = "INSERT INTO $table ($paramNames) VALUES ($paramPlaceholders)";

        $result = self::$connection->prepare($query);
        return $result->execute($params);
    }

    public static function updateById($table, $id, $params)
    {
        $updateColumnsString = DB::generateUpdateColumnsString($params);
        $params['id'] = $id;

        $query = "UPDATE $table SET $updateColumnsString WHERE id = :id";

        $result = self::$connection->prepare($query);
        return $result->execute($params);
    }

    public static function generateWhereString($params) {
        return join(' AND ', array_map(function($key) {
            return $key . '= :' . $key;
        }, array_keys($params)));
    }

    public static function generateUpdateColumnsString($params) {
        return join(', ', array_map(function($key) {
            return $key . '= :' . $key;
        }, array_keys($params)));
    }
}