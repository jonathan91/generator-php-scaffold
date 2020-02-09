<?php
declare(strict_types = 1);

namespace App\Service\Handler\<%= className %>;


use App\Service\Command\AbstractCommand;
use App\Service\Handler\AbstractHandler;
use App\Entity\<%= className %>;

class PostHandler extends AbstractHandler
{
   /**
     * 
     * {@inheritDoc}
     * @see \App\Service\Handler\AbstractHandler::handle()
     */
    public function handle(AbstractCommand $command)
    {
        $entity = new <%= className %>([
            <% attributs.fields.forEach(function(element, index, elements){ %>'<%= element.fieldName %>'=>$command-><%= element.fieldName %><% if (index !== elements.length - 1){ %>,
            <% }}); %>
        ]);
        $error = $this->validator->validate($entity);
        if (count($error) == 0) {
            $this->em->persist($entity);
            $this->em->flush();
            return $entity;
        }
        return $error;
    }
}
