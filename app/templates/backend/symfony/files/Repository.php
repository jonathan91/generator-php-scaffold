<?php
namespace <%= packageName %>\Repository;

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
		
		$query = $this->createQueryBuilder('tbl');
		if(isset($params['query'])){
			$query->where("tbl.nome like :nome")
			->setParameter("nome", "%{$params['query']}%");
		}else if(is_array($params)){
			unset($params['page']);
			unset($params['size']);
			unset($params['order']);
			unset($params['sort']);
			foreach ($params as $proprety=>$value){
				$query->andWhere("tbl.{$proprety} like :{$proprety}")
				->setParameter("{$proprety}", "%{$value}%");
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
    /*
    * The duplication rules are changed with the use case rules
    *
    * @param $id integer
    * @return mixed
    */
    public function findDuplicated($id)
    {
        $query = $this->createQueryBuilder('tbl');
        $query->select('tbl.id')
            ->where('tbl.id = :id')
            ->setParameter('id', $id);
        return $query->getQuery()->getResult();
    }
}