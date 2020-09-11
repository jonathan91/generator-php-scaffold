<?php
namespace App\Service\Query;

use App\Entity\<%= _.startCase(className).replace(' ', '') %>;

class <%= _.startCase(className).replace(' ', '') %>Query extends AbstractQuery
{
    /**
     *
     * {@inheritDoc}
     * @see \App\Service\Query\QueryInterface::getRepository()
     */
    public function getRepository()
    {
        return $this->em->getRepository(<%= _.startCase(className).replace(' ', '') %>::class);
    }
}
