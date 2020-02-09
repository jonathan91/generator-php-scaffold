<?php
declare(strict_types = 1); 

namespace App\Service\Query;

use Doctrine\ORM\EntityManager;

class <%= className %>Query implements QueryInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function getRepository()
    {
        return $this->em->getRepository('App:<%= className %>');
    }

    public function search($parameters)
    {
    	return $this->getRepository()->search($parameters);
    }

    public function findById(int $id)
    {
        return $this->getRepository()->find($id);
    }
}
