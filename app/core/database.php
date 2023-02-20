<?php

class Database
{
    public static $con;

    /**
     * Initializing Database Connection
     * 
     */
    public function __construct()
    {
        try {
            $string = DB_TYPE . ":host" . DB_HOST . ";dbname=" . DB_NAME;
            self::$con = new PDO($string, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$con) {
            return self::$con;
        }

        /**
         * Initiating class from within the function
         * 
         * @return DB_object
         */
        return $instance = new self();
    }

    public static function newInstance()
    {
        return $instance = new self();
    }

    /**
     * This function use to read data from database
     *
     * @return data
     */
    public function read($query, $data = array())
    {
        $stmt = self::$con->prepare($query);
        $result = $stmt->execute($data);

        if ($result) {
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (is_array($data) && count($data) > 0) {
                return $data;
            }
        }
        return false;
    }

    /**
     * This function use to write in database
     *
     */
    public function write($query, $data = array())
    {
        $stmt = self::$con->prepare($query);
        $result = $stmt->execute($data);

        if ($result) {
            return true;
        }
        return false;
    }
}
