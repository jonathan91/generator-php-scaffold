<?php declare(strict_types=1);
namespace App\Query;
 
use App\Entity\<%= _.startCase(className).replace(' ', '') %>;

class <%= _.startCase(className).replace(' ', '') %>Query extends AbstractQuery
{
    /**
     * 
     * {@inheritDoc}
     * @see \App\Query\AbstractQuery::getRepository()
     */
    public function getRepository()
    {
        return $this->em->getRepository(<%= _.startCase(className).replace(' ', '') %>::class);
    }
}