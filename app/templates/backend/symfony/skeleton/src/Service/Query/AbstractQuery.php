<?php

namespace App\Service\Query;

use Doctrine\ORM\EntityManager;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Service\Query\QueryInterface::getRepository()
     */
    abstract public function getRepository();

    /**
     *
     * {@inheritDoc}
     * @see \App\Service\Query\QueryInterface::search()
     */
    public function search($parameters = [])
    {
        return $this->getRepository()->search($parameters);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Service\Query\QueryInterface::findById()
     */
    public function findById(int $id)
    {
        return $this->getRepository()->findById($id);
    }
}
