<?php declare(strict_types=1);
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="<%= _.snakeCase(className).toLowerCase()%>")
 * @ORM\Entity(repositoryClass="App\Repository\<%= className %>Repository")
 */
class <%= _.startCase(className).replace(' ', '') %> extends AbstractEntity 
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="<%= _.snakeCase(className).toLowerCase() %>_id_seq", initialValue=1, allocationSize=1)
     */
    protected $id;
    <% attributs.fields.forEach(function(element){ %>
    /**
    * @var <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %>int<% } %>
    * @ORM\Column(name="<%= element.fieldName %>", type="<% if (element.fieldType !== 'class') { %><%= element.fieldType %>"<% } else { %>integer"<% } if (element.fieldValidateRules.includes('required')) { %>, nullable=false<% } if (element.fieldValidateRules.includes('unique')) { %>, unique=true<% } %>)
    * <% if (element.fieldType === 'class') { %>
    * @ORM\<%= element.relationshipType %>(targetEntity="App\Entity\<%= _.startCase(element.otherEntityName).replace(' ', '') %>")
    * @ORM\JoinColumn(name="id", referencedColumnName="<%= _.snakeCase(element.fieldName) %>")
    * <% } %>
    */
    
    protected $<%= _.camelCase(element.fieldName).replace(' ','') %>;
    <% }); %>
    public function getId(): ?int
    {
        return $this->id;
    }
    <% attributs.fields.forEach(function(element){ %>
    public function get<%= _.upperFirst(_.camelCase(element.fieldName)) %>(): <%if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %><% } else { %>int<% }} else { %> <%= element.otherEntityName %><% } %>
    {
        return $this-><%= _.camelCase(element.fieldName).replace(' ','') %>;
    }
    
    public function set<%= _.upperFirst(_.camelCase(element.fieldName)) %>(<%if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %><% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %> $<%= _.camelCase(element.fieldName).replace(' ','') %>): <%= _.startCase(className).replace(' ', '') %>
    {
        $this-><%= _.camelCase(element.fieldName).replace(' ','') %> = $<%= _.camelCase(element.fieldName).replace(' ','') %>;
        return $this;
    }
    <% }); %>
}