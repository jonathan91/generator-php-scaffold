<?php
namespace Tests\Unit\<%= packageName %>\Service\Handler\<%= className %>;

use Tests\Unit\AbstractUnitTestCase;
use <%= packageName %>\Entity\<%= className %>;
use <%= packageName %>\Service\Command\<%= className %>\Put<%= className %>Command;
use <%= packageName %>\Service\Handler\<%= className %>\Put<%= className %>Handler;

class <%= className %>HandlerTest extends AbstractUnitTestCase
{

    public function testUpdateSuccess()
    {
        $command = new Put<%= className %>Command(1, '1');
        $emMock = $this->getEntityManagerMock(<%= className %>::class);
        $handler = new Put<%= className %>Handler($emMock);
        $update = $handler->handle($command);
        $this->assertEquals('1', $update->get<%= _.upperFirst(_.camelCase(attributs.fields[0].fieldName)) %>());
    }

    public function testThrowExceptionNotFindEntity()
    {
        $command = new Put<%= className %>Command(100, 'S');
        $emMock = $this->getEntityManagerMock(<%= className %>::class);
        $emMock->mockery_findExpectation('find', [])->andReturn(null);
        $handler = new Put<%= className %>Handler($emMock);
        $handler->handle($command);
    }
}