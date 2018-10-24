<?php

namespace Helper;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    //For security purpose, change where credentials are stored
    private $dsn = '';
    private $user = '';
    private $password = '';
    private $db;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try {
            $this->db = new PDO($this->dsn, $this->user, $this->password);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Log::logWrite($e->getMessage());
            echo $e;
        }
    }

    /**
     * @param $sql
     * @return array|bool
     */
    public function select($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $sql
     * @return PDOStatement|bool
     */
    public function prepareQuery($sql)
    {

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;

        } catch (\PDOException $e) {
            Log::logWrite($e->getMessage());
            echo $e;
        }
        return false;
    }

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    } 

    /**
     * @param $parameter array
     */
    public function setParameter($parameter)
    {
        $this->parameter[] = $parameter;
    }
    }
}
