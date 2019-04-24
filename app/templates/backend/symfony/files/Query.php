<?php
declare(strict_types = 1); 

namespace <%= packageName %>\Service\Query;

use Doctrine\ORM\EntityManager;
use <%= packageName %>\Repository\<%= className %>Repository;
use <%= packageName %>\Entity\<%= className %>;

class <%= className %>Query
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected function get<%= className %>Repository(): <%= className %>Repository
    {
        return $this->em->getRepository('<%= packageName %>:<%= className %>');
    }

    public function search($parameters)
    {
    	return $this->get<%= className %>Repository()->search($parameters->all());
    }

    public function findById(int $id): <%= className %>
    {
        return $this->get<%= className %>Repository()->find($id);
    }
}
