<?php

namespace Model\Entity;

class StandardEntity
{
    protected $something;

    /**
     * StandardEntity constructor.
     * @param $something
     */
    public function __construct($something)
    {
        $this->something = intval($something);
    }

    /**
     * @return array
     */
    public function extract()
    {
        return get_object_vars($this);
    }
}
