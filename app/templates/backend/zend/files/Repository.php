<?php
namespace <%= packageName %>\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Repository\AppInterfaceRepository;

class <%= className %>Repository  extends EntityRepository implements AppInterfaceRepository
{

    public function search(array $params = [])
    {
		$offset = $params['page']==0 ? 0 : ($params['size']*$params['page']);
		$order  = $params['order'];
		$query = $this->createQueryBuilder('tbl');

		unset($params['page']);
		unset($params['size']);
		unset($params['order']);

		foreach ($params as $proprety=>$value){
			$query->andWhere("tbl.{$proprety} like :{$proprety}")
			->setParameter("{$proprety}", "%{$value}%");
		}

		$query->setFirstResult($offset);
		return $query->getQuery()->getResult();
    }
}
