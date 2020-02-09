<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="<%= _.snakeCase(className).toLowerCase()%>")
 * @ORM\Entity(repositoryClass="App\Repository\<%= className %>Repository")
 */
class <%= className %> implements \JsonSerializable 
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
    * <% } if (element.fieldValidateRules.includes('pattern')) { %>
    * @Assert\Regex(
    *     pattern="<%= element.fieldValidateRulesPattern %>",
    *     match=false,
    *     message="Your regular expression validation message"
    * )
    * <% } if (element.fieldValidateRules.includes('minlength') || element.fieldValidateRules.includes('maxlength')) { %>
    * @Assert\Range(
    *      min = <% if (element.fieldValidateRulesMin !== undefined){ %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>,
    *      max = <% if (element.fieldValidateRulesMax !== undefined){ %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>,
    *      minMessage = "The min value required is {{ limit }}",
    *      maxMessage = "The max value required is {{ limit }}"
    * )
    * <% } %>
     */
    
    private $<%= element.fieldName %>;
    <% }); %>
    public function __construct(<% attributs.fields.forEach(function(element, index, elements){ %><% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% } } else { %><%= element.otherEntityName %><% } %> $<%= element.fieldName %><% if (index !== elements.length - 1){ %>, <% }}); %>)
    {
        <% attributs.fields.forEach(function(element){ %>$this->set<%= _.upperFirst(_.camelCase(element.fieldName)) %>($<%= element.fieldName %>);
        <% }); %>
    }

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