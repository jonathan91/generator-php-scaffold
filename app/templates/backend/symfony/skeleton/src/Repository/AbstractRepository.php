<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class AbstractRepository extends EntityRepository
{
    protected $maxResult = 10;
    /**
     *
     * @param array $params
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function search(array $params)
    {
        $offset = ! isset($params['page']) ? 0 : ($params['size'] * $params['page']);
        $order = isset($params['order']) ? $params['order'] : '';
        $sort = isset($params['sort']) ? $params['sort'] : '';
        
        $query = $this->createQueryBuilder('tbl');
        unset($params['page']);
        unset($params['size']);
        unset($params['order']);
        unset($params['sort']);
        foreach ($params as $proprety => $value) {
            if (!is_numeric($value) && \DateTime::createFromFormat('Y-m-d', $value) === FALSE) {
                $query->andWhere("tbl.{$proprety} like :{$proprety}")->setParameter("{$proprety}", "%{$value}%");
            } else {
                $query->andWhere("tbl.{$proprety} = :{$proprety}")->setParameter("{$proprety}", $value);
            }
        }
        
        if (! empty($sort) && ! empty($order)) {
            $query->orderBy("tbl.{$sort}", $order);
        }
        $query->setFirstResult($offset);
        $query->setMaxResults($this->maxResult);
        $paginator = new Paginator($query);
        $result['data'] = $paginator->getIterator();
        $result['total'] = $paginator->count();
        return $result;
    }
    
    /**
     *
     * @param int $id
     * @return mixed|NULL|\Doctrine\DBAL\Driver\Statement|array
     */
    public function findById(int $id)
    {
        $query = $this->createQueryBuilder('tbl');
        $query->andWhere("tbl.id = :id");
        $query->setParameter("id", $id);
        return $query->getQuery()->getOneOrNullResult();
    }
}

