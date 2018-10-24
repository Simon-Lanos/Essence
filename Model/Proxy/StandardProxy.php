<?php

namespace Model\Proxy;

use Helper\Proxy;
use Model\Entity\StandardEntity;

class StandardProxy extends Proxy
{
    /**
     * @param $value
     * @return StandardEntity
     */
    public function findOneBySomething($value)
    {
        $result = $this->select()
            ->from('standardTable')
            ->where('something = :something')->setParameter(array(':something' => $value))
            ->execute()
        ;
        $standardEntity = new StandardEntity($result['something']);
        return $standardEntity;
    }

    /**
     * @param $attr
     * @return StandardEntity
     */
    public function create($attr)
    {
        $standardEntity = new StandardEntity($attr);

        $entityData = $standardEntity->extract();
        $this->insert('standardTable');
        foreach ($entityData as $key => $value) {
            $this->set($key.' = :'.$key)->setParameter(array(':'.$key => $value));
        }

        return $standardEntity;
    }

    /**
     * @param $entity StandardEntity
     */
    public function persist(StandardEntity $entity)
    {
        $entityData = $entity->extract();
        $this->update('standardTable');
        foreach ($entityData as $key => $value) {
            $this->set($key.' = :'.$key)->setParameter(array(':'.$key => $value));
        }
        $this->where('something = :something')->setParameter(array(':something' => $entityData['something']))
            ->execute();
        ;
    }
}
