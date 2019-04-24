<?php
namespace Tests\Unit\<%= packageName %>\Entity;

use Tests\Unit\AbstractUnitTestCase;
use ReflectionProperty;
<% attributs.fields.forEach(function(element){ if (element.fieldType === 'class') { %>
use Mockery as M;
<% }}); %>
use <%= packageName %>\Entity\<%= className %>;<% attributs.fields.forEach(function(element){ if (element.fieldType === 'class'){ %>
use <%= packageName %>\Entity\<%= element.otherEntityName %>;
<% }}) %>
class <%= className %>Test extends AbstractUnitTestCase
{
    /**
     * @return <%= className %>
     */
    protected function build<%= className %>()
    {
        return new <%= className %>();
    }

    public function testGetId()
    {
        $entity = $this->build<%= className %>();
        $id = new ReflectionProperty($entity, 'id');
        $id->setAccessible(true);
        $id->setValue($entity, 10);
        $this->assertEquals(10, $entity->getId());
    }
    <% attributs.fields.forEach(function(element){ %>
    public function testGetSet<%= _.upperFirst(_.camelCase(element.fieldName)) %>()
    {
        <% if (element.fieldType === 'text' || element.fieldType === 'string') { %>
        $param = 'text to test';
        <% } else if (element.fieldType === 'date' || element.fieldType === 'time' || element.fieldType === 'datetime' || element.fieldType === 'datetimetz') { %>
        $param = new \DateTime();
        <% } else if (element.fieldType === 'integer' || element.fieldType === 'smallint' || element.fieldType ==='bigint') { %>
        $param = 50;
        <% } else if (element.fieldType === 'decimal' || element.fieldType === 'float') { %>
        $param = 44.3;
        <% } else if (element.fieldType === 'array' || element.fieldType ==='simple_array' || element.fieldType === 'json_array') { %>
        $param = element.fieldType === 'json_array' ? json_encode(['data1','data2']) : ['data1','data2'];
        <% } else if (element.fieldType === 'boolean') { %>
        $param = true;
        <% } else if (element.fieldType === 'class') { %>
        $mock = M::mock(<%= element.otherEntityName %>::class);
        $mock->shouldreceive('getId')->andReturn(1);
        $entity = $this->build<%= className %>();
        $entity->set<%= _.upperFirst(_.camelCase(element.fieldName)) %>($param);
        $this->assertEquals($mock, $entity->getServidor());
        $this->assertEquals(1, $entity->getServidor()->getId());
        <% } if (element.fieldType !== 'class') { %>
        $entity = $this->build<%= className %>();
        $entity->set<%= _.upperFirst(_.camelCase(element.fieldName)) %>($param);
        $this->assertEquals($param, $entity->get<%= _.upperFirst(_.camelCase(element.fieldName)) %>());
        <% } %>
    }
    <% }); %>
}