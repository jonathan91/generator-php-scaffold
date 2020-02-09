<?php
declare(strict_types = 1);

namespace App\Service\Command\<%= className %>;

use Symfony\Component\Validator\Constraints as Assert;<% attributs.fields.forEach(function(element){ if (element.fieldType === 'class') { %>
use <%= packageName %>\Entity\<%= element.otherEntityName %>;
<%}}); %>
class <%= attributs.type %><%= className %>Command
{
    /**
	 * @var int
	 */
	public $id;
    <% if (attributs.type !== 'Delete') { attributs.fields.forEach(function(element){ %>
    /**
    * @var <% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %>
    * <% if (element.fieldValidateRules.includes('required')) { %>
    * @Assert\NotBlank()
    * <% } if (element.fieldValidateRules.includes('pattern')) { %> 
    * @Assert\Regex(pattern="<%= element.fieldValidateRulesPattern %>", match=false, message="Your regular expression validation message")
    * <% } if (element.fieldValidateRules.includes('minlength') || element.fieldValidateRules.includes('maxlength')) { %>
    * @Assert\Length(
    *    min = <% if (element.fieldValidateRulesMin !== undefined) { %><%= element.fieldValidateRulesMin %><% } else { %><%=0%><% } %>, 
    *    max = <% if (element.fieldValidateRulesMax !== undefined) { %><%= element.fieldValidateRulesMax %><% } else { %><%=60%><% } %>))
    * <% } %>
    */
    public $<%= element.fieldName %>;
    <% }); } %>
    public function __construct(<% if (attributs.type === 'Put') { %>int $id,<% } if (attributs.type === 'Delete') { %>int $id<% } else { attributs.fields.forEach(function(element, index, elements){ %><% if (element.fieldType !== 'class') { if (element.fieldType !== 'integer') { %><%= element.fieldType %> <% } else { %>int<% }} else { %><%= element.otherEntityName %><% } %> $<%= element.fieldName %><% if (index !== elements.length - 1){ %>, <% }});} %>)
    {
        <% if (attributs.type !== 'Post') { %>$this->id = $id;<% } %>
        <% if (attributs.type !== 'Delete') { %>
        <% attributs.fields.forEach(function(element){ %>$this-><%= element.fieldName %> = $<%= element.fieldName %>;
        <% }); %>
        <% } %>
    }
}