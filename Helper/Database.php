<?php

class Database
{
    //Faille de sécurité - Prévoir moyen de se connecter à la ddb plus "sécurisé"
    private $dsn = '';
    private $userdsn = '';
    private $passworddsn = '';

    private $db;

    function __construct()
    {
        try {
            $this->db = new PDO($this->dsn, $this->userdsn, $this->passworddsn);
            $this -> db -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Log::logWrite($e->getMessage());
            echo $e;
        }
    }

    function select($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function prepareQuery($sql)
    {

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;

        } catch (PDOException $e) {
            Log::logWrite($e->getMessage());
            echo $e;
        }
        return false;
    }

    function __destruct()
    {
        unset($this->db);
    }
}
