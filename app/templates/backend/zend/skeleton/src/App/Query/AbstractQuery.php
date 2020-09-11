<?php
namespace App\Query;

use Doctrine\ORM\EntityManager;

abstract class AbstractQuery
{

    /**
     *
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
     * @return void
     */
    abstract public function getRepository();

    /**
     *
     * @param array $parameters
     * @return void
     */
    public function search($parameters = [])
    {
        return $this->getRepository()->search($parameters);
    }

    /**
     *
     * @param integer $id
     * @return void
     */
    public function findById(int $id)
    {
        $response = $this->getRepository()->findById($id);
        return $response->toJson();
    }
}

