<?php
declare(strict_types = 1);

namespace <%= packageName %>\Service\Handler\<%= className %>;

use Doctrine\ORM\EntityManager;
use <%= packageName %>\Entity\<%= className %>;
use <%= packageName %>\Service\Command\<%= className %>\Post<%= className %>Command;

class Post<%= className %>Handler
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handle(Post<%= className %>Command $command): <%= className %>
    {
        $entity = $this->em->getRepository('<%= packageName %>:<%= className %>')->findDuplicated($command->id);
        if ($entity && $entity[0]['id'] != (int)$command->id) {
            throw new \Exception('The record already exist!');
        }
        $entity = new <%= className %>(
            <% attributs.fields.forEach(function(element, index, elements){ %>$command-><%= element.fieldName %><% if (index !== elements.length - 1){ %>,
            <% }}); %>
        );
        $this->em->persist($entity);
        return $entity;
    }
}
