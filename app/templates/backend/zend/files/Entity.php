<?php
namespace <%= packageName %>\Entity;

use Application\Entity\AppAbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="<%= _.snakeCase(className).toLowerCase()%>")
 * @ORM\Entity(repositoryClass="\<%= packageName %>\Repository\<%= className %>Repository")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("<%= className %>")
 */
class <%= className %> extends AppAbstractEntity
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
    * @ORM\Column(name="<%= _.snakeCase(element.fieldName).toLowerCase() %>", type="<% if (element.fieldType !== 'class') { %><%= element.fieldType %>"<% } else { %>integer"<% } if (element.fieldValidateRules.includes('unique')) { %>, unique=true<% } %>)
    * <% if (element.fieldType === 'class') { %>
    * @ORM\<%= element.relationshipType %>(targetEntity="<%= packageName %>\Entity\<%= element.otherEntityName %>")
    * @ORM\JoinColumn(name="id", referencedColumnName="<%= _.snakeCase(element.fieldName) %>")<% } if (element.fieldValidateRules.includes('required')) { %>
    * @Annotation\Required({"required":"true"})<% } if (element.fieldValidateRules.includes('minlength') || element.fieldValidateRules.includes('maxlength')) { %>
    * @Annotation\Validator({"name":"StringLength", "options":{"min":"<% if (element.fieldValidateRulesMin !== undefined){ %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>","max":"<% if (element.fieldValidateRulesMax !== undefined){ %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>"}})
    * <% } %>
    */
    protected $<%= element.fieldName %>;
    <% }); %>
    public function __construct($data)
    {
       $this->setValues($data);
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
    <% attributs.fields.forEach(function(element){ %>
    public function get<%= _.upperFirst(_.camelCase(element.fieldName)) %>()
    {
        return $this-><%= element.fieldName %>;
    }
    
    public function set<%= _.upperFirst(_.camelCase(element.fieldName)) %>(<%if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %><% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %> $<%= element.fieldName %>)
    {
        $this-><%= element.fieldName %> = $<%= element.fieldName %>;
    }<% }); %>
}
