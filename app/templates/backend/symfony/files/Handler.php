<?php
namespace App\Service\Handler\<%= _.startCase(className).replace(' ', '') %>;

use App\Service\Command\AbstractCommand;
use App\Service\Handler\AbstractHandler;
use App\Entity\<%= _.startCase(className).replace(' ', '') %>;

class <%= attributs.type %>Handler extends AbstractHandler
{
    /**
     *
     * {@inheritdoc}
     * @see \App\Service\Handler\AbstractHandler::handle()
     */
    public function handle(AbstractCommand $command)
    {
        return $this-><%= _.toLower(attributs.type) %>(new <%= _.startCase(className).replace(' ', '') %>(), $command);
    }
}