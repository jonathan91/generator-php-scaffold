<?php declare(strict_types=1);
namespace App\Handler\<%= _.startCase(className).replace(' ', '') %>;

use App\Command\AbstractCommand;
use App\Handler\AbstractHandler;
use App\Entity\AbstractEntity;

class <%= attributs.type %>Handler extends AbstractHandler
{
    /**
     * 
     * {@inheritDoc}
     * @see \App\Handler\AbstractHandler::process()
     */
    public function process(AbstractEntity $entity, AbstractCommand $command)
    {
        return $this-><%= _.lowerCase(attributs.type) %>($entity, $command);
    }
}