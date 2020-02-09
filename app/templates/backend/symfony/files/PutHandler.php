<?php
declare(strict_types = 1);

namespace App\Service\Handler\<%= className %>;

use App\Service\Command\AbstractCommand;
use App\Service\Handler\AbstractHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PutHandler extends AbstractHandler
{
    /**
     *
     * {@inheritdoc}
     * @see \App\Service\Handler\AbstractHandler::handle()
     */
    public function handle(AbstractCommand $command)
    {   
        $entity = $this->em->getRepository('App:<%= className %>')->find($command->id);
        if (empty($entity)) {
            throw new NotFoundHttpException("The id {$command->id} record not found");
        }
        $entity->setValues([
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
}
