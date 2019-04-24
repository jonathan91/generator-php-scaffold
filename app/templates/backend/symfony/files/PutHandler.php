<?php
declare(strict_types = 1);

namespace <%= packageName %>\Service\Handler\<%= className %>;

use Doctrine\ORM\EntityManager;
use <%= packageName %>\Exception\NotFoundException;
use <%= packageName %>\Entity\<%= className %>;
use <%= packageName %>\Service\Command\<%= className %>\Put<%= className %>Command;

class Put<%= className %>Handler
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(Put<%= className %>Command $command): <%= className %>
    {   
        $entity = $this->em->getRepository('<%= packageName %>:<%= className %>')->findDuplicated($command->id);
        if ($entity && $entity[0]['id'] != (int)$command->id) {
            throw new \Exception('The record already exist!');
        }
        $entity = $this->em->find('<%= packageName %>:<%= className %>', $command->id);
        if (!$entity) {
            throw new NotFoundException('Record not found!');
        }
        <% attributs.fields.forEach(function(element, index, elements){ %>$entity->set<%= _.upperFirst(_.camelCase(element.fieldName)) %>($command-><%= element.fieldName %>);
        <% }); %>
        $this->em->persist($entity);
        return $entity;
    }
}
