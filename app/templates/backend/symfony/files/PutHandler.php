<?php
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
        $error = $this->validator->validate($command);
        if ($error->count() == 0) {
            $entity = $this->em->getRepository('App:<%= className %>')->find($command->id);
            if (empty($entity)) {
                throw new NotFoundHttpException("The id {$command->id} record not found");
            }
            $entity->setValues([
                <% attributs.fields.forEach(function(element, index, elements){ %>'<%= element.fieldName %>'=>$command-><%= element.fieldName %><% if (index !== elements.length - 1){ %>,
                <% }}); %>
            ]);
            $this->em->persist($entity);
            $this->em->flush();
            return $entity;
        }
        return $error;
    }
}
