<?php

namespace Helper;

abstract class Proxy
{
    const LANGUAGE_MY_SQL = 1;
    const NO_MODE = null;
    const MODE_SELECT = 'select';
    const MODE_UPDATE = 'update';
    const MODE_INSERT = 'insert';

    private $query;
    private $language;
    private $db;
    private $mode;
    private $hasSet;

    /**
     * Proxy constructor.
     */
    public function __construct()
    {
        $this->language = self::LANGUAGE_MY_SQL;
        $this->db = new Database();
        $this->mode = self::NO_MODE;
        $this->hasSet = false;
    }

    /**
     * @return int
     */
    private function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $queryPart
     */
    private function addToQuery($queryPart)
    {
        $this->query .= ' ' . $queryPart;
    }

    /**
     * @return string
     */
    private function getQueryString()
    {
        return trim($this->query) . ';';
    }

    /**
     * @return bool
     */
    private function isSelect()
    {
        if ($this->mode === self::MODE_SELECT) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $mode
     */
    private function setModeTo($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return $this
     */
    private function setHasSet()
    {
        $this->hasSet = true;
        return $this;
    }

    /**
     * @return bool
     */
    private function hasSet()
    {
        return $this->hasSet;
    }

    /**
     * @return $this
     */
    private function flushQuery()
    {
        $this->query = '';
        $this->setModeTo(self::NO_MODE);
        return $this;
    }

    /**
     * @return $this
     */
    protected function select()
    {
        $this->setModeTo(self::MODE_SELECT);

        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL:
                if (func_num_args() === 0) {
                    $stmt = '*';
                } else {
                    for ($i = 0, $stmt = ''; $i < func_num_args(); $i++) {
                        $stmt .= '`' . func_get_arg($i) . '`,';
                    }
                    //delete the last ","
                    $stmt = substr_replace($stmt, '', -1);
                }
                $select = 'SELECT ' . $stmt;
                break;
            default:
                die();
        }

        $this->addToQuery($select);

        return $this;
    }

    /**
     * @param $stmt string
     * @return $this
     */
    protected function update($stmt)
    {
        $this->setModeTo(self::MODE_UPDATE);

        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL:
                $update = 'UPDATE `' . $stmt . '`';
                break;
            default:
                die();
        }

        $this->addToQuery($update);

        return $this;
    }

    /**
     * @param $stmt string
     * @return $this
     */
    protected function insert($stmt)
    {
        $this->setModeTo(self::MODE_INSERT);

        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL:
                $insert = 'INSERT ' . 'INTO `' . $stmt . '`';
                break;
            default:
                die();
        }

        $this->addToQuery($insert);

        return $this;
    }

    /**
     * @param $stmt string
     * @return $this
     */
    protected function from($stmt)
    {
        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL;
                $from = 'FROM `' . $stmt . '`';
                break;
            default:
                die();
        }

        $this->addToQuery($from);

        return $this;
    }

    /**
     * @param $stmt string
     * @return $this
     */
    protected function set($stmt)
    {
        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL;
                if ($this->hasSet()) {
                    $set = ', ' . $stmt;
                } else {
                    $set = 'SET ' . $stmt;
                }
                break;
            default:
                die();
        }

        $this->setHasSet()->addToQuery($set);

        return $this;
    }

    /**
     * @param $stmt
     * @return $this
     */
    protected function where($stmt)
    {
        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL;
                $where = 'WHERE ' . $stmt;
                break;
            default:
                die();
        }

        $this->addToQuery($where);

        return $this;
    }

    /**
     * @param $stmt
     * @return $this
     */
    protected function andX($stmt)
    {
        switch ($this->getLanguage()) {
            case self::LANGUAGE_MY_SQL;
                $and = 'AND ' . $stmt;
                break;
            default:
                die();
        }

        $this->addToQuery($and);

        return $this;
    }

    /**
     * @param $parameter array
     * @return $this
     */
    protected function setParameter(array $parameter)
    {
        $this->db->setParameter($parameter);
        return $this;
    }

    /**
     * @return \PDOStatement|array|bool
     */
    protected function execute()
    {
        if ($this->isSelect()) {
            $result = $this->db->select($this->getQueryString());
            if (count($result) === 1) {
                $result = $result[0];
            }
        } else {
            $result = $this->db->prepareQuery($this->getQueryString());
        }
        $this->flushQuery();
        return $result;
    }
}
