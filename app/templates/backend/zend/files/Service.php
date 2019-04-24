<?php
namespace <%= packageName %>\Service;

use Application\Service\AppAbstractEntityService;

class <%= className %>Service extends AppAbstractEntityService
{

    public function find($id)
    {
        try {
            $repository = $this->getOrmEntityManager()->getRepository('<%= packageName %>\Entity\<%= className %>');
            return $repository->find($id);
        } catch (\Exception $exceptionError){
            throw new \Exception($exceptionError->getMessage(), $exceptionError->getCode());
        }
    }

    public function search($where = [])
    {
        try {
            $repository = $this->getOrmEntityManager()->getRepository('<%= packageName %>\Entity\<%= className %>');
            return $repository->search($where);
        } catch (\Exception $exceptionError){
            throw new \Exception($exceptionError->getMessage(), $exceptionError->getCode());
        }
    }
}
