<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="<%= _.snakeCase(className).toLowerCase()%>")
 * @ORM\Entity(repositoryClass="App\Repository\<%= className %>Repository")
 */
class <%= className %> extends AbstractEntity 
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="<%= _.snakeCase(className).toLowerCase() %>_id_seq", initialValue=1, allocationSize=1)
     */
    private $id;
    <% attributs.fields.forEach(function(element){ %>
    /**
    * @var <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %>int<% } %>
    * @ORM\Column(name="<%= element.fieldName %>", type="<% if (element.fieldType !== 'class') { %><%= element.fieldType %>"<% } else { %>integer"<% } if (element.fieldValidateRules.includes('required')) { %>, nullable=true<% } if (element.fieldValidateRules.includes('unique')) { %>, unique=true<% } %>)
    * <% if (element.fieldType === 'class') { %>
    * @ORM\<%= element.relationshipType %>(targetEntity="App\Entity\<%= element.otherEntityName %>")
    * @ORM\JoinColumn(name="id", referencedColumnName="<%= _.snakeCase(element.fieldName) %>")
    * <% } %>
    */
    
    private $<%= element.fieldName %>;
    <% }); %>
    public function getId(): int
    {
        return $this->id;
    }
    <% attributs.fields.forEach(function(element){ %>
    public function get<%= _.upperFirst(_.camelCase(element.fieldName)) %>(): <%if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %><% } else { %>int<% }} else { %> <%= element.otherEntityName %><% } %>
    {
        return $this-><%= element.fieldName %>;
    }
    
    public function set<%= _.upperFirst(_.camelCase(element.fieldName)) %>(<%if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %><% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %> $<%= element.fieldName %>): <%= className %>
    {
        $this-><%= element.fieldName %> = $<%= element.fieldName %>;
        return $this;
    }
    <% }); %>
}