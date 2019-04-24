<?php
declare(strict_types = 1);

namespace <%= packageName %>\Service\Handler\<%= className %>;

use Doctrine\ORM\EntityManager;
use <%= packageName %>\Entity\<%= className %>;
use <%= packageName %>\Exception\NotFoundException;
use <%= packageName %>\Service\Command\<%= className %>\Delete<%= className %>Command;

class Delete<%= className %>Handler
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(Delete<%= className %>Command $command): <%= className %>
    {
        $entity = $this->em->find('<%= packageName %>:<%= className %>', $command->id);
        if (!$entity) {
            throw new NotFoundException('Record not found!');
        }
        $this->em->remove($entity);
        return $entity;
    }
}
