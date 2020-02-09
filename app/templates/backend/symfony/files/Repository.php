<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class <%= className %>Repository extends EntityRepository
{
    
    /**
     * @param $params array
     * @return mixed|array
     */
    public function search(array $params)
    {
		$offset = !isset($params['page']) ? 0 : ($params['size']*$params['page']);
		$order  = isset($params['order']) ? $params['order'] : '';
		$sort   = isset($params['sort']) ? $params['sort'] : ''; 
		unset($params['page']);
		unset($params['size']);
		unset($params['order']);
		unset($params['sort']);
		$query = $this->createQueryBuilder('tbl');
		
		foreach ($params as $proprety=>$value){
            if(is_string($value)){
                $query->andWhere("tbl.{$proprety} like :{$proprety}")
                ->setParameter("{$proprety}", "%{$value}%");
            } else {
                $query->andWhere("tbl.{$proprety} = :{$proprety}")
                ->setParameter("{$proprety}", $value);
            }
		}
		
		if(!empty($sort) && !empty($order)){
			$query->orderBy("tbl.{$sort}", $order);
		}

		$query->setFirstResult($offset);
		$query->setMaxResults(10);
		$paginator = new Paginator($query);
		$result['data'] = $paginator->getIterator();
		$result['total'] = $paginator->count();
		return $result;
    }
}