<?php
namespace Tests\Unit\<%= packageName %>\Service\Query;

use Tests\Unit\AbstractUnitTestCase;
use Mockery as M;
use <%= packageName %>\Entity\<%= className %>;
use <%= packageName %>\Service\Query\<%= className %>Query;
use <%= packageName %>\Repository\<%= className %>Repository;

class <%= className %>QueryTest extends AbstractUnitTestCase
{

    protected function get<%= className %>Repository()
    {
        $entity = M::mock(<%= className %>::class)->makePartial();
        $repositoryMock = M::mock(<%= className %>Repository::class);
        $repositoryMock->shouldReceive('find')->andReturn($entity);
        $repositoryMock->shouldReceive('findAll')->andReturn([$entity]);
        $emMock = $this->getEntityManagerMock();
        $emMock->shouldReceive('getRepository')->andReturn($repositoryMock);
        return new <%= className %>Query($emMock);
    }

    public function testSearchSuccess()
    {
        $service = $this->get<%= className %>Query();
        $data = $service->search();
        $this->assertCount(1, $data);
        $this->assertInstanceOf(<%= className %>::class, $data[0]);
    }

    public function testFindByIdSuccess()
    {
        $service = $this->get<%= className %>Query();
        $data = $service->findById(1);
        $this->assertInstanceOf(<%= className %>::class, $data);
    }
}